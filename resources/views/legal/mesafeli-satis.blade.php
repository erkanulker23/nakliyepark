@extends('layouts.app')

@section('title', 'Mesafeli Satış Sözleşmesi - NakliyePark')
@section('meta_description', 'NakliyePark mesafeli satış sözleşmesi. 6502 sayılı Tüketicinin Korunması Hakkında Kanun kapsamında elektronik ortamda akdedilen sözleşme.')

@section('content')
<div class="page-container py-8 sm:py-12">
    <header class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Mesafeli Satış Sözleşmesi</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1">6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler Yönetmeliği kapsamında düzenlenmiştir.</p>
    </header>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm prose prose-zinc dark:prose-invert max-w-none">
        <h2 class="text-lg font-semibold mt-6 mb-2">1. Taraflar</h2>
        <p><strong>SATICI / HİZMET SAĞLAYICI:</strong> NakliyePark platformunu işleten taraf (ünvan, adres, MERSIS no. ve iletişim bilgileri site üzerinden veya ödeme sayfasında yer alır).</p>
        <p><strong>ALICI / TÜKETİCİ:</strong> Platform üzerinden nakliye hizmeti veya ilgili ödeme işlemini gerçekleştiren gerçek veya tüzel kişi.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">2. Sözleşmenin Konusu</h2>
        <p>İşbu sözleşme, alıcının satıcıya ait elektronik ortamda (NakliyePark ve ilgili alt yapılar) siparişini verdiği hizmetin satışı ve ifası ile ilgili tarafların hak ve yükümlülüklerini düzenler. Hizmet, mesafeli satış kapsamında elektronik ortamda akdedilen sözleşme ile belirlenir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">3. Sipariş ve Ödeme</h2>
        <p>Alıcı, ön bilgilendirme formu ve mesafeli satış sözleşmesini elektronik ortamda onaylayarak siparişi verir. Ödeme; kredi kartı, banka kartı veya platformda sunulan diğer ödeme yöntemleri ile alınabilir. Ödeme bilgileri 6563 sayılı Elektronik Ticaretin Düzenlenmesi Hakkında Kanun ve ödeme kuruluşu gerekliliklerine uygun işlenir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">4. Cayma Hakkı</h2>
        <p>Yürürlükteki mevzuat uyarınca, hizmetin ifasına tüketicinin onayı ile başlanmış olması halinde cayma hakkı kullanılamaz. Nakliye hizmeti tarih ve saat bağımlı olduğundan, taraflarca aksi kararlaştırılmadıkça hizmet ifası başladıktan sonra cayma hakkı kullanılamaz. Cayma hakkının kullanılabildiği durumlarda süre 14 gündür.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">5. Şikayet ve Uyuşmazlık</h2>
        <p>Tüketici şikayetleri için satıcının iletişim kanalları kullanılır. Uyuşmazlıklarda Tüketici Hakem Heyetleri ve Tüketici Mahkemeleri yetkilidir.</p>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-8">Son güncelleme: {{ now()->translatedFormat('d F Y') }}. Bu metin bilgilendirme amaçlıdır; hukuki metin avukat kontrolünden geçirilmelidir.</p>
    </div>
</div>
@endsection
