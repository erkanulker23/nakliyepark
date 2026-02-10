@extends('layouts.app')

@section('title', 'KVKK Aydınlatma Metni - NakliyePark')
@section('meta_description', 'NakliyePark kişisel verilerin işlenmesine ilişkin aydınlatma metni.')

@section('content')
<div class="page-container py-8 sm:py-12 max-w-3xl mx-auto">
    <div class="prose prose-zinc dark:prose-invert max-w-none">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white mb-6">Kişisel Verilerin İşlenmesine İlişkin Aydınlatma Metni</h1>

        <p class="text-zinc-600 dark:text-zinc-400">
            6698 sayılı Kişisel Verilerin Korunması Kanunu (“KVKK”) kapsamında, NakliyePark olarak kişisel verilerinizi veri sorumlusu sıfatıyla işlemekteyiz. Bu metin, ihale talebi oluştururken toplanan verileriniz hakkında sizi bilgilendirmek amacıyla hazırlanmıştır.
        </p>

        <h2 class="text-lg font-semibold mt-6">1. İşlenen Kişisel Veriler</h2>
        <p class="text-zinc-600 dark:text-zinc-400">
            İhale (taşınma talebi) oluşturma sırasında ad soyad, e-posta adresi, telefon numarası, adres bilgileri (il, ilçe, mahalle, sokak) ve talep ile ilgili açıklama ile fotoğraflar toplanabilmektedir.
        </p>

        <h2 class="text-lg font-semibold mt-6">2. İşleme Amaçları</h2>
        <p class="text-zinc-600 dark:text-zinc-400">
            Toplanan verileriniz; taşınma talebinizin oluşturulması, nakliye firmalarına gösterilmesi ve teklif almanız, sizinle iletişim kurulması ve hukuki yükümlülüklerin yerine getirilmesi amacıyla işlenmektedir.
        </p>

        <h2 class="text-lg font-semibold mt-6">3. Veri Saklama Süresi</h2>
        <p class="text-zinc-600 dark:text-zinc-400">
            Kişisel verileriniz, talebiniz kapandıktan veya iş tamamlandıktan sonra en fazla {{ config('nakliyepark.data_retention_months', 24) }} ay süreyle saklanır; bu süre sonunda silinir veya anonim hale getirilir. Yasal saklama zorunlulukları saklıdır.
        </p>

        <h2 class="text-lg font-semibold mt-6">4. Haklarınız</h2>
        <p class="text-zinc-600 dark:text-zinc-400">
            KVKK’nın 11. maddesi kapsamında kişisel verilerinizin işlenip işlenmediğini öğrenme, işlenmişse buna ilişkin bilgi talep etme, işlenme amacını ve amacına uygun kullanılıp kullanılmadığını öğrenme, yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri bilme, eksik veya yanlış işlenmişse düzeltilmesini isteme, silinmesini veya yok edilmesini isteme ve otomatik sistemler vasıtasıyla analiz edilmesi suretiyle aleyhinize bir sonucun ortaya çıkmasına itiraz etme haklarına sahipsiniz. Bu haklarınızı kullanmak için bizimle iletişime geçebilirsiniz.
        </p>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-8">
            Son güncelleme: {{ now()->format('d.m.Y') }}
        </p>
    </div>
</div>
@endsection
