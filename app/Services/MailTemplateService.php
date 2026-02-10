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
}
