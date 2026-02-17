<footer class="bg-white dark:bg-zinc-950 border-t border-zinc-200 dark:border-zinc-800 safe-bottom pb-20 lg:pb-0">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 sm:gap-10">
            <div>
                @if(!empty($site_logo_url ?? null) || !empty($site_logo_dark_url ?? null))
                    <a href="{{ url('/') }}" class="inline-block mb-4">
                        @if(!empty($site_logo_url))
                            <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-10 w-auto max-w-[160px] object-contain dark:hidden">
                        @endif
                        @if(!empty($site_logo_dark_url ?? null))
                            <img src="{{ $site_logo_dark_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-10 w-auto max-w-[160px] object-contain hidden dark:block">
                        @elseif(!empty($site_logo_url))
                            <img src="{{ $site_logo_url }}" alt="{{ $site_meta_title ?? 'NakliyePark' }}" class="h-10 w-auto max-w-[160px] object-contain hidden dark:block">
                        @endif
                    </a>
                @endif
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">NakliyePark</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('ihale.create') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">İhale başlat</a></li>
                    <li><a href="{{ route('faq.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">SSS</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Keşfet</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('ihaleler.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">İhaleler</a></li>
                    @if($show_firmalar_page ?? true)
                    <li><a href="{{ route('firmalar.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Firmalar</a></li>
                    @endif
                    <li><a href="{{ route('defter.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Defter</a></li>
                    <li><a href="{{ route('pazaryeri.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Pazaryeri</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">Araçlar</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('tools.volume') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Hacim hesaplama</a></li>
                    <li><a href="{{ route('tools.distance') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Mesafe hesaplama</a></li>
                    <li><a href="{{ route('tools.road-distance') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Karayolu mesafe</a></li>
                    <li><a href="{{ route('tools.checklist') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Taşınma kontrol listesi</a></li>
                    <li><a href="{{ route('tools.moving-calendar') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Taşınma takvimi</a></li>
                    <li><a href="{{ route('tools.price-estimator') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Tahmini fiyat</a></li>
                    @if($show_firmalar_page ?? true)
                    <li><a href="{{ route('tools.company-lookup') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Firma sorgula</a></li>
                    @endif
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">İletişim</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('contact.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">İletişim</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Blog</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 sm:mt-10 pt-6 sm:pt-8 border-t border-zinc-200 dark:border-zinc-800">
            @if(config('app.beta', true))
                <p class="text-xs sm:text-sm text-amber-600 dark:text-amber-400 mb-2">NakliyePark şu an <strong>beta</strong> sürümündedir. Geliştirme devam ediyor; geri bildiriminiz bizim için değerli.</p>
            @endif
            <p class="text-xs sm:text-sm text-zinc-500 dark:text-zinc-400">NakliyePark — Akıllı nakliye ihalesi ve yük borsası. Üye olmadan ihale başlatabilirsiniz.</p>
        </div>
    </div>
</footer>
