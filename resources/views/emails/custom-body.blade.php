@extends('emails.layout')
@section('body')
{{-- Özel e-posta gövdesi (admin panelden düzenlenen mail şablonları) --}}
<div class="message-content" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 15px; line-height: 1.7; color: #334155;">
{!! $body !!}
</div>
@endsection
