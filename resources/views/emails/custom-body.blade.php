@extends('emails.layout')
@section('body')
{{-- Özel e-posta gövdesi (admin panelden düzenlenen mail şablonları veya buildBodyHtml çıktısı) --}}
<div class="message-content" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; font-size: 15px; line-height: 1.65; color: #475569;">
{!! $body !!}
</div>
@endsection
