<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class BlogAiService
{
    /**
     * Yapay zeka ile blog yazısı oluşturur.
     *
     * @return array{title: string, excerpt: string, content: string, meta_title: string, meta_description: string}|null
     */
    public function generate(string $topic, ?string $additionalInstructions = null): ?array
    {
        $apiKey = \App\Models\Setting::get('openai_api_key', '') ?: config('openai.api_key');
        if (empty($apiKey)) {
            throw new \RuntimeException('OpenAI API anahtarı girilmemiş. Admin paneli > Ayarlar > API / Yapay Zeka sekmesinden girin veya .env dosyasına OPENAI_API_KEY ekleyin.');
        }

        $systemPrompt = <<<PROMPT
Sen nakliye ve taşımacılık sektörü için blog yazıları oluşturan uzman bir içerik yazarısın.
NakliyePark web sitesi için Türkçe, SEO uyumlu blog yazıları üretirsin.
Yazılar profesyonel, bilgilendirici ve okuyucu dostu olmalıdır.

ÖNEMLİ: "content" alanı MUTLAKA geçerli HTML olmalı. Markdown veya düz metin KULLANMA.
- Her paragraf <p>...</p> içinde olmalı.
- Alt başlıklar <h2>...</h2> veya <h3>...</h3> kullan.
- Listeler <ul><li>...</li></ul> veya <ol><li>...</li></ol>.
- Vurgu için <strong> veya <em> kullan.
- Paragraflar tek satırda yazılabilir; etiketler korunmalı.

Yanıtını MUTLAKA aşağıdaki JSON formatında ver. Başka hiçbir metin ekleme, sadece JSON:
{
  "title": "Yazı başlığı (çekici, 60 karakter civarı)",
  "excerpt": "Kısa özet (150-200 karakter, liste ve SEO için)",
  "content": "Ana içerik. Sadece HTML: <p>, <h2>, <h3>, <strong>, <ul>, <li>, <a>. Her paragraf <p> ile sarılı olmalı.",
  "meta_title": "SEO meta başlık (55-60 karakter)",
  "meta_description": "SEO meta açıklama (150-160 karakter)"
}
PROMPT;

        $userPrompt = "Şu konuda bir blog yazısı oluştur: {$topic}";
        if (! empty($additionalInstructions)) {
            $userPrompt .= "\n\nEk talimatlar: {$additionalInstructions}";
        }

        try {
            $response = OpenAI::chat()->create([
                'model' => \App\Models\Setting::get('openai_blog_model', '') ?: env('OPENAI_BLOG_MODEL', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'response_format' => ['type' => 'json_object'],
                'temperature' => 0.7,
                'max_tokens' => 4000,
            ]);

            $content = $response->choices[0]->message->content;
            if (empty($content)) {
                return null;
            }

            $data = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Blog AI: JSON parse hatası', ['content' => $content]);

                return null;
            }

            return [
                'title' => $data['title'] ?? '',
                'excerpt' => $data['excerpt'] ?? '',
                'content' => $data['content'] ?? '',
                'meta_title' => $data['meta_title'] ?? '',
                'meta_description' => $data['meta_description'] ?? '',
            ];
        } catch (\Throwable $e) {
            Log::error('Blog AI hatası', [
                'message' => $e->getMessage(),
                'topic' => $topic,
            ]);
            throw $e;
        }
    }
}
