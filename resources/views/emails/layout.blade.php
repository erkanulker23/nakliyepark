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
        .wrapper { width: 100%; table-layout: fixed; background-color: #f8fafc; }
        .content { max-width: 600px; margin: 0 auto; }
        .email-card { border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06); }
        .header-bg { background-color: #059669; }
        .footer-bg { background-color: #f1f5f9; }
        @media only screen and (max-width: 600px) {
            .content, .inner-content { width: 100% !important; max-width: 100% !important; }
            .mobile-pad { padding-left: 24px !important; padding-right: 24px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table role="presentation" class="wrapper" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f8fafc;">
        <tr>
            <td align="center" style="padding: 48px 24px;">
                <table role="presentation" class="content email-card" width="600" cellspacing="0" cellpadding="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px;">
                    {{-- Header (logo + marka) --}}
                    <tr>
                        <td class="mobile-pad" style="padding: 28px 40px 24px; background-color: #ffffff;">
                            @php
                                $logoPath = \App\Models\Setting::get('site_logo', '');
                                $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
                                $siteName = config('seo.site_name', 'NakliyePark');
                            @endphp
                            <a href="{{ url('/') }}" style="font-size: 0; text-decoration: none; display: inline-flex; align-items: center; gap: 12px;">
                                @if($logoUrl)
                                    <img src="{{ $logoUrl }}" alt="{{ $siteName }}" width="140" height="44" style="display: block; max-height: 44px; width: auto; object-fit: contain;">
                                @else
                                    <span style="font-size: 22px; font-weight: 700; color: #059669; letter-spacing: -0.02em;">{{ $siteName }}</span>
                                @endif
                            </a>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td class="mobile-pad" style="padding: 0 40px 36px; background-color: #ffffff;">
                            @yield('body')
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td class="footer-bg mobile-pad" style="padding: 24px 40px 28px; border-radius: 0 0 16px 16px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="border-top: 1px solid #e2e8f0; padding-top: 20px;">
                                        <p style="margin: 0 0 8px; font-size: 12px; color: #64748b; line-height: 1.5;">
                                            Bu e-posta <strong style="color: #475569;">{{ config('seo.site_name', 'NakliyePark') }}</strong> tarafından gönderilmiştir.
                                        </p>
                                        <p style="margin: 0; font-size: 12px;">
                                            <a href="{{ url('/') }}" style="color: #059669; text-decoration: none; font-weight: 500;">Siteyi ziyaret et</a>
                                            @if(!empty($contact_email = \App\Models\Setting::get('contact_email')))
                                                <span style="color: #cbd5e1;"> · </span>
                                                <a href="mailto:{{ $contact_email }}" style="color: #059669; text-decoration: none; font-weight: 500;">İletişim</a>
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
