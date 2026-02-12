@extends('emails.layout')
@section('body')
<p style="margin: 0 0 16px; font-size: 16px; line-height: 1.6; color: #334155;">Merhaba,</p>
<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #334155;">Bu e-posta mail ayarlarınızı test etmek için gönderilmiştir. Bu mesajı aldıysanız SMTP ayarlarınız doğru yapılandırılmış ve sistem mailleri (kayıt, ihale, teklif vb.) sorunsuz iletilecektir.</p>
<p style="margin: 0; font-size: 15px; line-height: 1.6; color: #64748b;">{{ config('seo.site_name', 'NakliyePark') }} – Tüm sistem e-postaları bu şablon ve güvenli gönderim ile iletilir.</p>
@endsection
