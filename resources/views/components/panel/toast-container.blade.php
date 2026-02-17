{{-- Session flash'leri toast gibi göstermek için container. İçeriği sayfa yüklenince JS veya Alpine ile doldurulabilir. --}}
<div id="panel-toast-container" class="panel-toast-container" aria-live="polite" role="status">
    @if(session('success'))
        <div class="panel-card p-4 border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-200 shadow-lg toast-enter" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="panel-card p-4 border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950/40 text-red-800 dark:text-red-200 shadow-lg toast-enter" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if(session('info'))
        <div class="panel-card p-4 border-sky-200 dark:border-sky-800 bg-sky-50 dark:bg-sky-950/40 text-sky-800 dark:text-sky-200 shadow-lg toast-enter" role="alert">
            {{ session('info') }}
        </div>
    @endif
</div>
