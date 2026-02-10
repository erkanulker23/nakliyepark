<footer class="bg-white dark:bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800 safe-bottom">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-8 sm:gap-10">
            <div>
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
                    <li><a href="{{ route('firmalar.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Firmalar</a></li>
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
                    <li><a href="{{ route('tools.cost') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Tahmini maliyet</a></li>
                    <li><a href="{{ route('tools.checklist') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Taşınma kontrol listesi</a></li>
                    <li><a href="{{ route('tools.moving-calendar') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Taşınma takvimi</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-zinc-900 dark:text-white uppercase tracking-wider mb-4">İletişim</h3>
                <ul class="space-y-2.5">
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-zinc-600 dark:text-zinc-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Blog</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-10 pt-8 border-t border-zinc-200 dark:border-zinc-800">
            <p class="text-sm text-zinc-500 dark:text-zinc-500">NakliyePark — Akıllı nakliye ihalesi ve yük borsası. Üye olmadan ihale başlatabilirsiniz.</p>
        </div>
    </div>
</footer>
