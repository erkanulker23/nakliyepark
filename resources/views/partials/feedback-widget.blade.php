{{-- Beta geri bildirim: sabit buton + sağdan açılan panel (sadece beta modunda gösterilir) --}}
@if(config('app.beta', true))
<div id="feedback-widget" class="fixed bottom-24 sm:bottom-8 right-4 z-40 flex flex-col items-end gap-0">
    {{-- Panel: sağdan slide-in --}}
    <div id="feedback-panel" class="hidden w-full max-w-md sm:max-w-sm bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-2xl shadow-2xl overflow-hidden transition-transform duration-300 ease-out translate-x-full mb-3" aria-hidden="true">
        <div class="p-4 sm:p-5 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Geri bildirim</h3>
            <button type="button" id="feedback-panel-close" class="p-2 rounded-xl text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition-colors" aria-label="Paneli kapat">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-4 sm:p-5">
            <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-4">NakliyePark beta aşamasında. Görüş ve önerileriniz bizim için çok değerli.</p>
            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-3" id="feedback-form">
                @csrf
                <div class="absolute -left-[9999px] opacity-0 h-0 overflow-hidden" aria-hidden="true">
                    <label for="feedback_company_website">Web siteniz</label>
                    <input type="text" name="{{ \App\Services\SpamGuard::HONEYPOT_FIELD }}" id="feedback_company_website" tabindex="-1" autocomplete="off">
                </div>
                <div>
                    <label for="feedback_name" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Adınız *</label>
                    <input type="text" name="name" id="feedback_name" required maxlength="255" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}" class="input-touch w-full rounded-xl text-sm" placeholder="Adınız">
                    @error('name', 'feedback')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="feedback_email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">E-posta *</label>
                    <input type="email" name="email" id="feedback_email" required value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}" class="input-touch w-full rounded-xl text-sm" placeholder="ornek@email.com">
                    @error('email', 'feedback')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="feedback_message" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Geri bildiriminiz *</label>
                    <textarea name="message" id="feedback_message" required maxlength="3000" rows="4" class="input-touch w-full rounded-xl text-sm min-h-[100px]" placeholder="Görüş, öneri veya hata bildirimi...">{{ old('message') }}</textarea>
                    @error('message', 'feedback')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-start gap-2">
                    <input type="checkbox" name="kvkk_consent" id="feedback_kvkk" value="1" required class="mt-1 rounded border-zinc-300 dark:border-zinc-600 text-emerald-600 focus:ring-emerald-500" {{ old('kvkk_consent') ? 'checked' : '' }}>
                    <label for="feedback_kvkk" class="text-xs text-zinc-600 dark:text-zinc-400">Kişisel verilerimin <a href="{{ route('kvkk.aydinlatma') }}" target="_blank" rel="noopener noreferrer" class="underline hover:text-emerald-600">KVKK</a> kapsamında işlenmesini kabul ediyorum. *</label>
                </div>
                @error('kvkk_consent', 'feedback')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @if(config('services.turnstile.site_key'))
                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
                @endif
                <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-medium text-sm transition-colors">Gönder</button>
            </form>
        </div>
    </div>

    {{-- Açılır buton: mobilde sadece ikon, masaüstünde ikon + metin --}}
    <button type="button" id="feedback-toggle" class="flex items-center justify-center gap-2 w-12 h-12 sm:w-auto sm:h-auto sm:px-4 sm:py-3 rounded-full bg-amber-700 hover:bg-amber-600 text-white text-sm font-medium shadow-lg hover:shadow-xl transition-all focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-zinc-900" aria-label="Geri bildirim gönder">
        <svg class="w-5 h-5 sm:w-5 sm:h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
        <span class="hidden sm:inline">Geri bildirim</span>
    </button>
</div>

<script>
(function() {
    var widget = document.getElementById('feedback-widget');
    var panel = document.getElementById('feedback-panel');
    var toggle = document.getElementById('feedback-toggle');
    var closeBtn = document.getElementById('feedback-panel-close');
    if (!widget || !panel || !toggle) return;

    function openPanel() {
        panel.classList.remove('hidden', 'translate-x-full');
        panel.classList.add('translate-x-0');
        panel.setAttribute('aria-hidden', 'false');
        toggle.setAttribute('aria-expanded', 'true');
    }
    function closePanel() {
        panel.classList.remove('translate-x-0');
        panel.classList.add('translate-x-full', 'hidden');
        panel.setAttribute('aria-hidden', 'true');
        toggle.setAttribute('aria-expanded', 'false');
    }

    var shouldOpen = {{ (session('feedback_open') || (isset($errors) && $errors->getBag('feedback') && $errors->getBag('feedback')->isNotEmpty())) ? 'true' : 'false' }};
    if (shouldOpen) openPanel();

    toggle.addEventListener('click', function() {
        if (panel.classList.contains('hidden')) openPanel(); else closePanel();
    });
    closeBtn && closeBtn.addEventListener('click', closePanel);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && panel && !panel.classList.contains('hidden')) closePanel();
    });
})();
</script>
@if(config('services.turnstile.site_key'))
@push('scripts')
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
@endpush
@endif
@endif
