@extends('layouts.app')

@section('title', 'Ön Bilgilendirme Formu - NakliyePark')
@section('meta_description', 'NakliyePark mesafeli satış ön bilgilendirme formu. Sipariş öncesi tüketici bilgilendirmesi (6502 sayılı Kanun).')

@section('content')
<div class="page-container py-8 sm:py-12">
    <header class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900 dark:text-white tracking-tight">Ön Bilgilendirme Formu</h1>
        <p class="text-zinc-500 dark:text-zinc-400 mt-1">Mesafeli sözleşmelerde tüketicilerin sipariş öncesi bilgilendirilmesi (6502 sayılı Kanun ve ilgili yönetmelik).</p>
    </header>

    <div class="rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 sm:p-8 shadow-sm prose prose-zinc dark:prose-invert max-w-none">
        <h2 class="text-lg font-semibold mt-6 mb-2">1. Satıcı / Hizmet Sağlayıcı Bilgileri</h2>
        <p>Ünvan, adres, MERSIS numarası, iletişim bilgileri (telefon, e-posta) ve varsa şikayet birimi, platform ve ödeme sayfalarında tüketiciye sunulur.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">2. Hizmetin Temel Nitelikleri</h2>
        <p>Nakliye (ev/ofis taşıma) hizmeti; çıkış ve varış adresleri, taşınacak eşya, tarih/saat ve ihale veya teklif üzerinden belirlenen kapsam ve fiyat ile sunulur. Hizmetin ayrıntıları sipariş/teklif ekranında gösterilir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">3. Toplam Fiyat ve Ödeme</h2>
        <p>Toplam fiyat (KDV dahil/dahil değil ayrıca belirtilir), ödeme şekilleri (kredi kartı, banka kartı vb.) ve taksit imkânları sipariş öncesi ekranda gösterilir. Ek vergi veya ücretler varsa açıkça belirtilir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">4. Teslimat / İfa</h2>
        <p>Hizmetin ifa tarihi ve saati, taraflarca kabul edilen taşınma günü/saati olup sipariş veya teklif onayında teyit edilir.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">5. Cayma Hakkı</h2>
        <p>Tüketicinin onayı ile hizmetin ifasına başlanmış olması halinde cayma hakkı kullanılamaz. Diğer hallerde yasal cayma süresi ve koşulları mesafeli satış sözleşmesinde yer alır.</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">6. Yasal Haklar</h2>
        <p>Tüketici, mesafeli satış sözleşmesi ve ön bilgilendirme formunu sipariş öncesi okuyup onaylar. Tüketici Hakem Heyetleri ve Tüketici Mahkemeleri yasal çerçevede yetkilidir.</p>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-8">Son güncelleme: {{ now()->translatedFormat('d F Y') }}. Bu metin bilgilendirme amaçlıdır; hukuki metin avukat kontrolünden geçirilmelidir.</p>
    </div>
</div>
@endsection
