<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Varsayılan e-posta şablonları (Admin > Ayarlar > Mail şablonları)
    |--------------------------------------------------------------------------
    | Veritabanında kayıtlı değer yoksa bu metinler formda ve gönderimde kullanılır.
    | Konu ve gövdede {from_city}, {to_city}, {site_name}, {name}, {action_url},
    | {verification_url}, {firma_adi}, {teklif_tutar}, {musteri_adi}, {reset_url} kullanılabilir.
    */

    'admin_new_ihale' => [
        'subject' => 'Yeni ihale talebi - {from_city} → {to_city}',
        'body' => '<p><strong>Yeni ihale talebi</strong></p>
<p>Yeni bir nakliye talebi oluşturuldu (Üye/Misafir).</p>
<p>{from_city} → {to_city}</p>
<p><a href="{action_url}">İhaleyi incele</a></p>',
    ],

    'email_verification' => [
        'subject' => 'E-posta adresinizi doğrulayın - {site_name}',
        'body' => '<p>Merhaba {name}!</p>
<p>Hesabınızı oluşturdunuz. E-posta adresinizi doğrulamak için aşağıdaki linke tıklayın:</p>
<p><a href="{verification_url}">E-postamı doğrula</a></p>
<p>Bu link 60 dakika geçerlidir. Eğer hesap oluşturmadıysanız bu e-postayı dikkate almayın.</p>',
    ],

    'musteri_welcome' => [
        'subject' => 'Hoş geldiniz! - {site_name}',
        'body' => '<p>Merhaba {name}!</p>
<p>{site_name} ailesine hoş geldiniz.</p>
<p>Nakliye talebi oluşturarak firmalardan teklif alabilirsiniz.</p>
<p><a href="{action_url}">Panele git</a></p>',
    ],

    'nakliyeci_welcome' => [
        'subject' => 'Hoş geldiniz! - {site_name}',
        'body' => '<p>Merhaba {name}!</p>
<p>{site_name} ailesine hoş geldiniz.</p>
<p>Firma bilgilerinizi tamamlayıp onay aldıktan sonra ihalelere teklif verebilirsiniz.</p>
<p><a href="{action_url}">Panele git</a></p>',
    ],

    'musteri_ihale_created' => [
        'subject' => 'Nakliye talebiniz alındı - {from_city} → {to_city}',
        'body' => '<p>Merhaba!</p>
<p>Nakliye talebiniz başarıyla alındı.</p>
<p>İnceleme sonrası talebiniz yayına alınacak ve nakliye firmaları size teklif gönderebilecek.</p>
<p><a href="{action_url}">Talebinizi görüntüle</a></p>',
    ],

    'musteri_ihale_published' => [
        'subject' => 'İhaleniz yayında - {from_city} → {to_city}',
        'body' => '<p>Merhaba!</p>
<p>İhale talebiniz onaylandı ve yayına alındı.</p>
<p>Nakliye firmaları artık size teklif gönderebilir. Gelen teklifleri panelinizden takip edebilirsiniz.</p>
<p><a href="{action_url}">İhalemi görüntüle</a></p>',
    ],

    'musteri_teklif_received' => [
        'subject' => 'Yeni teklif geldi - {from_city} → {to_city}',
        'body' => '<p>Merhaba!</p>
<p>{firma_adi} ihalenize <strong>{teklif_tutar}</strong> teklif verdi.</p>
<p><a href="{action_url}">Teklifi görüntüle</a></p>
<p>Diğer firmalardan da teklif gelebilir; hepsini karşılaştırıp birini seçebilirsiniz.</p>',
    ],

    'nakliyeci_ihale_preferred' => [
        'subject' => 'Sizi tercih eden bir ihale yayında - {from_city} → {to_city}',
        'body' => '<p>Merhaba!</p>
<p>Bir müşteri sizi tercih ederek taşınma talebi oluşturdu. İhale onaylandı ve yayına alındı.</p>
<p>Hemen teklif vererek müşteriye ulaşabilirsiniz.</p>
<p><a href="{action_url}">İhaleye git ve teklif ver</a></p>',
    ],

    'nakliyeci_teklif_accepted' => [
        'subject' => 'Teklifiniz kabul edildi - {from_city} → {to_city}',
        'body' => '<p>Tebrikler!</p>
<p>{from_city} → {to_city} ihalesinde <strong>{teklif_tutar}</strong> tutarındaki teklifiniz müşteri tarafından kabul edildi.</p>
<p>Müşteri sizinle iletişime geçebilir. Taşıma detaylarını birlikte netleştirebilirsiniz.</p>
<p><a href="{action_url}">Tekliflerim</a></p>',
    ],

    'nakliyeci_contact_message' => [
        'subject' => 'Müşteri mesajı - {from_city} → {to_city}',
        'body' => '<p>Merhaba!</p>
<p>{musteri_adi} sizinle iletişime geçmek istiyor (kabul ettiğiniz teklif üzerinden).</p>
<p>Müşteri ile iletişim bilgileriniz üzerinden iletişime geçebilirsiniz.</p>
<p><a href="{action_url}">Tekliflerim</a></p>',
    ],

    'password_reset' => [
        'subject' => 'Şifre sıfırlama - {site_name}',
        'body' => '<p>Merhaba!</p>
<p>Hesabınız için şifre sıfırlama talebinde bulundunuz.</p>
<p><a href="{reset_url}">Şifremi sıfırla</a></p>
<p>Bu link 60 dakika geçerlidir.</p>
<p>Eğer bu talebi siz yapmadıysanız, bu e-postayı dikkate almayın.</p>',
    ],
];
