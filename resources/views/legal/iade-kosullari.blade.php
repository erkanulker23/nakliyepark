@extends('layouts.app')

@section('title', 'İade ve Cayma Koşulları - NakliyePark')
@section('meta_description', 'NakliyePark iade ve cayma hakkı koşulları. Mesafeli satış kapsamında cayma ve iade süreçleri.')

@section('content')
<div class="page-container py-8 sm:py-12">
    <header class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">İade ve Cayma Koşulları</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1">6502 sayılı Kanun ve Mesafeli Sözleşmeler Yönetmeliği kapsamında cayma hakkı ve iade uygulaması.</p>
    </header>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm prose prose-zinc dark:prose-invert max-w-none">
        <h2 class="text-lg font-semibold mt-6 mb-2">1. Cayma Hakkı</h2>
        <p>Tüketici, hizmetin ifasına tarafların onayı ile başlanmamış olması koşuluyla, sipariş/onay tarihinden itibaren 14 (on dört) gün içinde herhangi bir gerekçe göstermeksizin ve cezai şart ödemeksizin cayma hakkına sahiptir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">2. Cayma Hakkının Kullanılamayacağı Durumlar</h2>
        <p>Tüketicinin onayı ile hizmetin ifasına başlanmış olması halinde cayma hakkı kullanılamaz. Nakliye hizmeti, taraflarca kararlaştırılan belirli bir tarih ve saatte ifa edildiğinden, taşınma günü/saati belirlenip tüketici onayı alındıktan sonra cayma hakkı yürürlükteki mevzuat uyarınca kullanılamaz.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">3. Cayma Bildirimi ve İade</h2>
        <p>Cayma hakkı kullanılacaksa, bu süre içinde satıcıya yazılı (elektronik ortam dahil) bildirimde bulunulur. Cayma hakkının kullanılması halinde, satıcı 14 gün içinde ödemeyi iade eder. İade, tüketicinin kullandığı ödeme aracına (kart vb.) yapılır; aynı araç kullanılamıyorsa tüketiciye bilgi verilerek alternatif yöntem uygulanabilir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">4. İade Süreci</h2>
        <p>İade işlemleri, ödeme alan kuruluş ve banka süreçlerine bağlı olarak tamamlanır. Gecikme halinde tüketici satıcı iletişim kanallarından bilgi alabilir.</p>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-8">Son güncelleme: {{ now()->translatedFormat('d F Y') }}. Bu metin bilgilendirme amaçlıdır; hukuki metin avukat kontrolünden geçirilmelidir.</p>
    </div>
</div>
@endsection
