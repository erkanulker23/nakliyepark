<?php

namespace App\Services;

class MailTemplateService
{
    /**
     * Şablon metnindeki {placeholder} değerlerini verilen dizinle değiştirir.
     */
    public static function replacePlaceholders(string $body, array $replacements): string
    {
        $siteName = config('seo.site_name', 'NakliyePark');
        $defaults = [
            '{site_name}' => $siteName,
        ];
        $replacements = array_merge($defaults, $replacements);
        return str_replace(array_keys($replacements), array_values($replacements), $body);
    }

    /**
     * Admin panelden kaydedilmiş özel mail gövdesi varsa placeholder'larla doldurulmuş metni döndürür; yoksa null.
     */
    public static function getCustomBody(string $templateKey, array $replacements): ?string
    {
        $body = \App\Models\Setting::get('mail_tpl_' . $templateKey . '_body', '');
        $body = trim((string) $body);
        if ($body === '') {
            return null;
        }
        return self::replacePlaceholders($body, $replacements);
    }

    /** Paragraf stili – modern, okunaklı */
    private static function pStyle(): string
    {
        return 'margin: 0 0 18px; font-size: 15px; line-height: 1.65; color: #475569;';
    }

    /** İlk/giriş paragrafı – vurgulu */
    private static function pStyleFirst(): string
    {
        return 'margin: 0 0 18px; font-size: 17px; line-height: 1.6; color: #1e293b; font-weight: 500;';
    }

    /** Ana buton stili – modern CTA (düz renk: e-posta istemci uyumluluğu) */
    private static function buttonStyle(): string
    {
        return 'display: inline-block; padding: 14px 28px; background-color: #059669; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 15px; border-radius: 10px;';
    }

    /**
     * Test maili ile aynı şablonu kullanan HTML gövde oluşturur.
     * Tüm sistem mailleri bu stil ile gider; view emails.custom-body + emails.layout kullanılır.
     *
     * @param  array<int, string>  $paragraphs  Paragraf metinleri (HTML olabilir)
     * @param  array<int, array{url: string, text: string}>|null  $buttons  Opsiyonel buton(lar)
     */
    public static function buildBodyHtml(array $paragraphs, ?array $buttons = null): string
    {
        $html = '';
        foreach ($paragraphs as $i => $text) {
            $style = $i === 0 ? self::pStyleFirst() : self::pStyle();
            $html .= '<p style="' . $style . '">' . $text . '</p>';
        }
        if ($buttons !== null && $buttons !== []) {
            $html .= '<p style="margin: 28px 0 0; font-size: 15px;">';
            foreach ($buttons as $i => $b) {
                if (isset($b['url'], $b['text'])) {
                    if ($i > 0) {
                        $html .= ' &nbsp; ';
                    }
                    $html .= '<a href="' . e($b['url']) . '" style="' . self::buttonStyle() . '">' . e($b['text']) . '</a>';
                }
            }
            $html .= '</p>';
        }
        return $html;
    }
}
