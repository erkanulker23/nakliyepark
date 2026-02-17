@extends('layouts.nakliyeci')

@section('title', 'Ödeme')
@section('page_heading', 'Ödeme')
@section('page_subtitle', 'Güvenli ödeme sayfasına yönlendiriliyorsunuz')

@section('content')
<div class="max-w-2xl">
    <div class="admin-card p-6 text-center">
        <p class="text-slate-600 dark:text-slate-400 mb-4">Kredi kartı bilgilerinizi gireceğiniz güvenli iyzico sayfasına yönlendiriliyorsunuz. Sayfa açılmazsa aşağıdaki butona tıklayın.</p>
        <div id="iyzico-form-wrap">
            {!! $checkout_form_content !!}
        </div>
        <p class="mt-4 text-xs text-slate-500 dark:text-slate-400">Güvenli ödeme iyzico altyapısı kullanılarak gerçekleştirilir.</p>
    </div>
</div>
<script>
(function() {
    var wrap = document.getElementById('iyzico-form-wrap');
    if (wrap) {
        var form = wrap.querySelector('form');
        if (form) {
            setTimeout(function() { form.submit(); }, 500);
        }
    }
})();
</script>
@endsection
