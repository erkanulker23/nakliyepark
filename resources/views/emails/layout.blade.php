{{-- Modern e-posta layout - tüm sistem mailleri bu şablonu kullanır --}}
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ config('seo.site_name', 'NakliyePark') }}</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        body, table, td, p, a, li { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f1f5f9; }
        .content { max-width: 600px; margin: 0 auto; }
        .brand { font-size: 22px; font-weight: 700; color: #059669; text-decoration: none; }
        .footer-text { font-size: 12px; color: #64748b; }
        .footer-link { color: #059669; text-decoration: none; }
        .button { display: inline-block; padding: 14px 28px; background-color: #059669; color: #ffffff !important; text-decoration: none; font-weight: 600; font-size: 15px; border-radius: 8px; }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" class="wrapper" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f1f5f9;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" class="content" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%;">
                    {{-- Header (logo + marka) --}}
                    <tr>
                        <td style="padding: 32px 40px 24px; background-color: #ffffff; border-radius: 12px 12px 0 0; border-bottom: 1px solid #e2e8f0;">
                            @php
                                $logoPath = \App\Models\Setting::get('site_logo', '');
                                $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
                            @endphp
                            <a href="{{ url('/') }}" class="brand" style="font-size: 22px; font-weight: 700; color: #059669; text-decoration: none; display: inline-flex; align-items: center; gap: 12px;">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ config('seo.site_name', 'NakliyePark') }}" width="140" height="44" style="display: block; max-height: 44px; width: auto; object-fit: contain;">
                                @endif
                                <span>{{ config('seo.site_name', 'NakliyePark') }}</span>
                            </a>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding: 32px 40px; background-color: #ffffff;">
                            @yield('body')
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 40px 32px; background-color: #ffffff; border-radius: 0 0 12px 12px; border-top: 1px solid #e2e8f0;">
                            <p class="footer-text" style="margin: 0; font-size: 12px; color: #64748b; line-height: 1.5;">
                                Bu e-posta {{ config('seo.site_name', 'NakliyePark') }} tarafından gönderilmiştir.<br>
                                <a href="{{ url('/') }}" class="footer-link" style="color: #059669; text-decoration: none;">Siteyi ziyaret et</a>
                                @if(!empty($contact_email = \App\Models\Setting::get('contact_email')))
                                    &nbsp;·&nbsp;<a href="mailto:{{ $contact_email }}" class="footer-link" style="color: #059669; text-decoration: none;">İletişim</a>
                                @endif
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
