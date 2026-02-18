<?php

use App\Http\Controllers\Admin\BlocklistController as AdminBlocklistController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\ConsentLogController as AdminConsentLogController;
use App\Http\Controllers\Admin\AdZoneController as AdminAdZoneController;
use App\Http\Controllers\Admin\DisputeController as AdminDisputeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\HomepageEditorController as AdminHomepageEditorController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\IhaleController as AdminIhaleController;
use App\Http\Controllers\Admin\MusteriController as AdminMusteriController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\SponsorController as AdminSponsorController;
use App\Http\Controllers\Admin\TeklifController as AdminTeklifController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\YukIlaniController as AdminYukIlaniController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DefterController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\GuestWizardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IhaleController;
use App\Http\Controllers\KvkkController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\Musteri\DashboardController as MusteriDashboardController;
use App\Http\Controllers\Musteri\IhaleController as MusteriIhaleController;
use App\Http\Controllers\Musteri\MesajController as MusteriMesajController;
use App\Http\Controllers\Musteri\NotificationController as MusteriNotificationController;
use App\Http\Controllers\Musteri\ProfileController as MusteriProfileController;
use App\Http\Controllers\Musteri\TeklifController as MusteriTeklifController;
use App\Http\Controllers\PazaryeriController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\Nakliyeci\BorcController as NakliyeciBorcController;
use App\Http\Controllers\Nakliyeci\CariController as NakliyeciCariController;
use App\Http\Controllers\Nakliyeci\CompanyController as NakliyeciCompanyController;
use App\Http\Controllers\Nakliyeci\DashboardController as NakliyeciDashboardController;
use App\Http\Controllers\Nakliyeci\EvraklarController as NakliyeciEvraklarController;
use App\Http\Controllers\Nakliyeci\GaleriController as NakliyeciGaleriController;
use App\Http\Controllers\Nakliyeci\IhaleController as NakliyeciIhaleController;
use App\Http\Controllers\Nakliyeci\LedgerController as NakliyeciLedgerController;
use App\Http\Controllers\Nakliyeci\LocationController as NakliyeciLocationController;
use App\Http\Controllers\Nakliyeci\NotificationController as NakliyeciNotificationController;
use App\Http\Controllers\Nakliyeci\OdemeController as NakliyeciOdemeController;
use App\Http\Controllers\Nakliyeci\PazaryeriController as NakliyeciPazaryeriController;
use App\Http\Controllers\Nakliyeci\PaketlerController as NakliyeciPaketlerController;
use App\Http\Controllers\Nakliyeci\ProfileController as NakliyeciProfileController;
use App\Http\Controllers\Nakliyeci\TeklifController as NakliyeciTeklifController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TurkeyLocationController;
use App\Http\Controllers\WizardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Türkiye il, ilçe, mahalle API (api.turkiyeapi.dev proxy)
Route::get('/api/turkey/provinces', [TurkeyLocationController::class, 'provinces'])->name('api.turkey.provinces');
Route::get('/api/turkey/districts', [TurkeyLocationController::class, 'districts'])->name('api.turkey.districts');
Route::get('/api/geocode', GeocodeController::class)->name('api.geocode');

Route::get('/ihaleler', [IhaleController::class, 'index'])->name('ihaleler.index');
Route::get('/ihaleler/{ihale}', [IhaleController::class, 'show'])->name('ihaleler.show');
Route::get('/nakliye-firmalari', [FirmaController::class, 'index'])->name('firmalar.index')->middleware('firmalar.visible');
Route::get('/nakliye-firmalari/haritadaki-nakliyeciler', [FirmaController::class, 'map'])->name('firmalar.map')->middleware('firmalar.visible');
Route::get('/nakliye-firmalari/{companyForShow}', [FirmaController::class, 'show'])->name('firmalar.show')->middleware('firmalar.visible');
Route::get('/defter', [DefterController::class, 'index'])->name('defter.index');
Route::get('/defter/ilan/{yukIlani}', [DefterController::class, 'show'])->name('defter.show');
Route::get('/pazaryeri', [PazaryeriController::class, 'index'])->name('pazaryeri.index');
Route::get('/pazaryeri/ilan/{listing}/{slug?}', [PazaryeriController::class, 'show'])->name('pazaryeri.show');

Route::get('/ihale/olustur', [GuestWizardController::class, 'index'])->middleware('not.nakliyeci')->name('ihale.create');
Route::post('/ihale/olustur', [GuestWizardController::class, 'store'])->middleware(['not.nakliyeci', 'throttle:10,1'])->name('ihale.store');

Route::get('/araclar/hacim', [ToolController::class, 'volume'])->name('tools.volume');
Route::get('/araclar/hacim/embed', [ToolController::class, 'volumeEmbed'])->name('tools.volume.embed');
Route::get('/araclar/mesafe', [ToolController::class, 'distance'])->name('tools.distance');
Route::get('/araclar/mesafe/embed', [ToolController::class, 'distanceEmbed'])->name('tools.distance.embed');
Route::redirect('/araclar/karayolu-mesafe', '/araclar/mesafe', 301);
Route::redirect('/araclar/karayolu-mesafe/embed', '/araclar/mesafe/embed', 301);
Route::get('/araclar/tasinma-kontrol-listesi', [ToolController::class, 'checklist'])->name('tools.checklist');
Route::get('/araclar/tasinma-takvimi', [ToolController::class, 'movingCalendar'])->name('tools.moving-calendar');
Route::get('/araclar/tahmini-fiyat', [ToolController::class, 'priceEstimator'])->name('tools.price-estimator');
Route::get('/araclar/tahmini-fiyat/embed', [ToolController::class, 'priceEstimatorEmbed'])->name('tools.price-estimator.embed');
Route::get('/firma-sorgula', [ToolController::class, 'companyLookup'])->name('tools.company-lookup');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/iletisim', [ContactController::class, 'index'])->name('contact.index');
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store')->middleware('throttle:5,1');
Route::get('/sss', [FaqController::class, 'index'])->name('faq.index');
Route::get('/kvkk-aydinlatma', [KvkkController::class, 'aydinlatma'])->name('kvkk.aydinlatma');
Route::get('/mesafeli-satis-sozlesmesi', [LegalController::class, 'mesafeliSatis'])->name('legal.mesafeli-satis');
Route::get('/on-bilgilendirme-formu', [LegalController::class, 'onBilgilendirme'])->name('legal.on-bilgilendirme');
Route::get('/iade-kosullari', [LegalController::class, 'iadeKosullari'])->name('legal.iade-kosullari');

// Admin girişi: guest zorunlu değil (eski oturum çerezi olsa bile form gösterilir), sadece throttle
Route::middleware(['throttle:20,1'])->group(function () {
    Route::get('/yonetici/admin', [LoginController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/yonetici/admin', [LoginController::class, 'loginAdmin'])->name('admin.login.submit');
});

// Login, kayıt, şifremi unuttum: dakikada 10 istek (429 önlemek için 6'dan artırıldı)
Route::middleware(['guest', 'throttle:10,1'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    // Şifremi unuttum (müşteri ve nakliyeci aynı giriş, aynı şifre sıfırlama)
    Route::get('/sifremi-unuttum', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/sifremi-unuttum', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/sifre-sifirla/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/sifre-sifirla', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// E-posta doğrulama: giriş yapmadan token (imzalı link) ile onaylanır
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::post('/email/verification-notification', [VerificationController::class, 'resend'])->middleware('throttle:3,1')->name('verification.send');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout.get')->middleware('auth');

Route::middleware(['auth', 'verified.panel'])->group(function () {
    Route::get('/wizard', fn () => redirect()->route('ihale.create'))->middleware('not.nakliyeci');
    Route::post('/wizard', [GuestWizardController::class, 'store'])->middleware(['not.nakliyeci', 'throttle:10,1'])->name('wizard.store');
});

Route::middleware(['auth', 'verified.panel', 'role:musteri'])->prefix('musteri')->name('musteri.')->group(function () {
    Route::get('/dashboard', [MusteriDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bilgilerim', [MusteriProfileController::class, 'edit'])->name('bilgilerim.edit');
    Route::put('/bilgilerim', [MusteriProfileController::class, 'update'])->name('bilgilerim.update');
    Route::post('/bilgilerim/sifre-sifirlama-gonder', [MusteriProfileController::class, 'sendPasswordResetLink'])->middleware('throttle:3,10')->name('bilgilerim.send-reset-link');
    Route::get('/teklifler', [MusteriTeklifController::class, 'index'])->name('teklifler.index');
    Route::get('/mesajlar', [MusteriMesajController::class, 'index'])->name('mesajlar.index');
    Route::get('/ihaleler/{ihale}', [MusteriIhaleController::class, 'show'])->name('ihaleler.show');
    Route::post('/ihaleler/{ihale}/kapat', [MusteriIhaleController::class, 'close'])->name('ihaleler.close');
    Route::post('/ihaleler/{ihale}/yayina-al', [MusteriIhaleController::class, 'open'])->name('ihaleler.open');
    Route::post('/ihaleler/{ihale}/bekleme', [MusteriIhaleController::class, 'pause'])->name('ihaleler.pause');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/kabul', [MusteriIhaleController::class, 'acceptTeklif'])->name('ihaleler.accept-teklif');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/reddet', [MusteriIhaleController::class, 'rejectTeklif'])->name('ihaleler.reject-teklif');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/kabul-geri-al', [MusteriIhaleController::class, 'undoAcceptTeklif'])->name('ihaleler.undo-accept');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/mesaj', [MusteriIhaleController::class, 'storeContactMessage'])->name('ihaleler.contact-message');
    Route::post('/ihaleler/{ihale}/uyusmazlik', [MusteriIhaleController::class, 'storeDispute'])->name('ihaleler.dispute.store');
    Route::get('/bildirimler', [MusteriNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/bildirimler/{id}/okundu', [MusteriNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/bildirimler/okundu-tumu', [MusteriNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});
Route::middleware(['auth', 'verified.panel'])->group(function () {
    Route::get('/ihale/{ihale}/degerlendir', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/degerlendirme', [ReviewController::class, 'store'])->name('review.store');
});

Route::middleware(['auth', 'verified.panel', 'role:nakliyeci'])->prefix('nakliyeci')->name('nakliyeci.')->group(function () {
    Route::get('/dashboard', [NakliyeciDashboardController::class, 'index'])->name('dashboard');
    Route::get('/bilgilerim', [NakliyeciProfileController::class, 'edit'])->name('bilgilerim.edit');
    Route::put('/bilgilerim', [NakliyeciProfileController::class, 'update'])->name('bilgilerim.update');
    Route::post('/bilgilerim/sifre-sifirlama-gonder', [NakliyeciProfileController::class, 'sendPasswordResetLink'])->middleware('throttle:3,10')->name('bilgilerim.send-reset-link');
    Route::get('/company/create', [NakliyeciCompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [NakliyeciCompanyController::class, 'store'])->name('company.store');
    Route::get('/company/edit', [NakliyeciCompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [NakliyeciCompanyController::class, 'update'])->name('company.update');
    Route::get('/teklifler', [NakliyeciTeklifController::class, 'index'])->name('teklifler.index');
    Route::get('/ledger', [NakliyeciLedgerController::class, 'index'])->name('ledger');
    Route::get('/ledger/olustur', [NakliyeciLedgerController::class, 'create'])->name('ledger.create');
    Route::post('/ledger', [NakliyeciLedgerController::class, 'store'])->name('ledger.store');
    Route::post('/ledger/ilan/{yukIlani}/yanit', [NakliyeciLedgerController::class, 'storeReply'])->name('ledger.reply.store')->middleware('throttle:30,1');
    Route::get('/bildirimler', [NakliyeciNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/bildirimler/{id}/okundu', [NakliyeciNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/bildirimler/okundu-tumu', [NakliyeciNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/galeri', [NakliyeciGaleriController::class, 'index'])->name('galeri.index');
    Route::get('/galeri/olustur', [NakliyeciGaleriController::class, 'create'])->name('galeri.create');
    Route::post('/galeri', [NakliyeciGaleriController::class, 'store'])->name('galeri.store');
    Route::delete('/galeri/{id}', [NakliyeciGaleriController::class, 'destroy'])->name('galeri.destroy');
    Route::get('/evraklar', [NakliyeciEvraklarController::class, 'index'])->name('evraklar.index');
    Route::get('/evraklar/olustur', [NakliyeciEvraklarController::class, 'create'])->name('evraklar.create');
    Route::post('/evraklar', [NakliyeciEvraklarController::class, 'store'])->name('evraklar.store');
    Route::delete('/evraklar/{id}', [NakliyeciEvraklarController::class, 'destroy'])->name('evraklar.destroy');
    Route::get('/cari', [NakliyeciCariController::class, 'index'])->name('cari.index');
    Route::get('/borc', [NakliyeciBorcController::class, 'index'])->name('borc.index');
    Route::get('/paketler', [NakliyeciPaketlerController::class, 'index'])->name('paketler.index');
    Route::post('/odeme/borc', [NakliyeciOdemeController::class, 'startBorc'])->name('odeme.start-borc');
    Route::post('/odeme/paket', [NakliyeciOdemeController::class, 'startPackage'])->name('odeme.start-package');
    Route::match(['get', 'post'], '/odeme/callback', [NakliyeciOdemeController::class, 'callback'])->name('odeme.callback');
    Route::get('/ihaleler', [NakliyeciIhaleController::class, 'index'])->name('ihaleler.index');
    Route::get('/ihaleler/{ihale}', [NakliyeciIhaleController::class, 'show'])->name('ihaleler.show');
    Route::post('/ihaleler/teklif', [NakliyeciIhaleController::class, 'storeTeklif'])->middleware('throttle:20,1')->name('ihaleler.teklif.store');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/guncelle', [NakliyeciIhaleController::class, 'requestTeklifUpdate'])->name('ihaleler.teklif.request-update');
    Route::post('/teklif', [NakliyeciTeklifController::class, 'store'])->middleware('throttle:20,1')->name('teklif.store');
    Route::post('/location', [NakliyeciLocationController::class, 'update'])->name('location.update');
    Route::get('/pazaryeri', [NakliyeciPazaryeriController::class, 'index'])->name('pazaryeri.index');
    Route::get('/pazaryeri/olustur', [NakliyeciPazaryeriController::class, 'create'])->name('pazaryeri.create');
    Route::post('/pazaryeri', [NakliyeciPazaryeriController::class, 'store'])->name('pazaryeri.store');
    Route::get('/pazaryeri/{pazaryeri}/duzenle', [NakliyeciPazaryeriController::class, 'edit'])->name('pazaryeri.edit');
    Route::put('/pazaryeri/{pazaryeri}', [NakliyeciPazaryeriController::class, 'update'])->name('pazaryeri.update');
    Route::delete('/pazaryeri/{pazaryeri}', [NakliyeciPazaryeriController::class, 'destroy'])->name('pazaryeri.destroy');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::post('/users/bulk-delete', [AdminUserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::post('/users/{user}/approve', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/send-company-reminder', [AdminUserController::class, 'sendCompanyReminder'])->name('users.send-company-reminder');
    Route::get('/musteriler', [AdminMusteriController::class, 'index'])->name('musteriler.index');
    Route::get('/musteriler/{user}', [AdminMusteriController::class, 'show'])->name('musteriler.show');
    Route::get('/consent-logs', [AdminConsentLogController::class, 'index'])->name('consent-logs.index');
    Route::get('/blocklist', [AdminBlocklistController::class, 'index'])->name('blocklist.index');
    Route::post('/blocklist/email', [AdminBlocklistController::class, 'storeEmail'])->name('blocklist.store-email');
    Route::post('/blocklist/phone', [AdminBlocklistController::class, 'storePhone'])->name('blocklist.store-phone');
    Route::post('/blocklist/ip', [AdminBlocklistController::class, 'storeIp'])->name('blocklist.store-ip');
    Route::delete('/blocklist/email/{blockedEmail}', [AdminBlocklistController::class, 'destroyEmail'])->name('blocklist.destroy-email');
    Route::delete('/blocklist/phone/{blockedPhone}', [AdminBlocklistController::class, 'destroyPhone'])->name('blocklist.destroy-phone');
    Route::delete('/blocklist/ip/{blockedIp}', [AdminBlocklistController::class, 'destroyIp'])->name('blocklist.destroy-ip');
    Route::post('/blocklist/user/{user}/block', [AdminBlocklistController::class, 'blockUser'])->name('blocklist.block-user');
    Route::post('/blocklist/user/{user}/unblock', [AdminBlocklistController::class, 'unblockUser'])->name('blocklist.unblock-user');
    Route::post('/blocklist/company/{company}/block', [AdminBlocklistController::class, 'blockCompany'])->name('blocklist.block-company');
    Route::post('/blocklist/company/{company}/unblock', [AdminBlocklistController::class, 'unblockCompany'])->name('blocklist.unblock-company');
    Route::get('/companies', [AdminCompanyController::class, 'index'])->name('companies.index');
    Route::get('/companies/{company}/edit', [AdminCompanyController::class, 'edit'])->name('companies.edit');
    Route::put('/companies/{company}', [AdminCompanyController::class, 'update'])->name('companies.update');
    Route::delete('/companies/{company}', [AdminCompanyController::class, 'destroy'])->name('companies.destroy');
    Route::post('/companies/{company}/approve', [AdminCompanyController::class, 'approve'])->name('companies.approve');
    Route::post('/companies/{company}/approve-pending', [AdminCompanyController::class, 'approvePendingChanges'])->name('companies.approve-pending');
    Route::post('/companies/{company}/reject', [AdminCompanyController::class, 'reject'])->name('companies.reject');
    Route::patch('/companies/{company}/package', [AdminCompanyController::class, 'updatePackage'])->name('companies.update-package');
    Route::post('/companies/{company}/logo/approve', [AdminCompanyController::class, 'approveLogo'])->name('companies.approve-logo');
    Route::post('/companies/{company}/galeri/approve-all', [AdminCompanyController::class, 'approveAllGalleryImages'])->name('companies.approve-gallery-all');
Route::post('/companies/{company}/galeri/{id}/approve', [AdminCompanyController::class, 'approveGalleryImage'])->name('companies.approve-gallery-image');
Route::delete('/companies/{company}/galeri/{id}', [AdminCompanyController::class, 'destroyGalleryImage'])->name('companies.destroy-gallery-image');
    Route::get('/ihaleler', [AdminIhaleController::class, 'index'])->name('ihaleler.index');
    Route::post('/ihaleler/bulk-publish', [AdminIhaleController::class, 'bulkPublish'])->name('ihaleler.bulk-publish');
    Route::post('/ihaleler/bulk-close', [AdminIhaleController::class, 'bulkClose'])->name('ihaleler.bulk-close');
    Route::post('/ihaleler/bulk-delete', [AdminIhaleController::class, 'bulkDelete'])->name('ihaleler.bulk-delete');
    Route::get('/ihaleler/create', [AdminIhaleController::class, 'create'])->name('ihaleler.create');
    Route::post('/ihaleler', [AdminIhaleController::class, 'store'])->name('ihaleler.store');
    Route::get('/ihaleler/{ihale}', [AdminIhaleController::class, 'show'])->name('ihaleler.show');
    Route::get('/ihaleler/{ihale}/edit', [AdminIhaleController::class, 'edit'])->name('ihaleler.edit');
    Route::put('/ihaleler/{ihale}', [AdminIhaleController::class, 'update'])->name('ihaleler.update');
    Route::delete('/ihaleler/{ihale}', [AdminIhaleController::class, 'destroy'])->name('ihaleler.destroy');
    Route::patch('/ihaleler/{ihale}/status', [AdminIhaleController::class, 'updateStatus'])->name('ihaleler.update-status');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/reject', [AdminIhaleController::class, 'rejectTeklif'])->name('ihaleler.teklif.reject');
    Route::get('/teklifler', [AdminTeklifController::class, 'index'])->name('teklifler.index');
    Route::get('/teklifler/{teklif}/edit', [AdminTeklifController::class, 'edit'])->name('teklifler.edit');
    Route::put('/teklifler/{teklif}', [AdminTeklifController::class, 'update'])->name('teklifler.update');
    Route::post('/teklifler/{teklif}/onayla-bekleyen', [AdminTeklifController::class, 'approvePendingUpdate'])->name('teklifler.approve-pending');
    Route::post('/teklifler/{teklif}/reddet-bekleyen', [AdminTeklifController::class, 'rejectPendingUpdate'])->name('teklifler.reject-pending');
    Route::delete('/teklifler/{teklif}', [AdminTeklifController::class, 'destroy'])->name('teklifler.destroy');
    Route::resource('yuk-ilanlari', AdminYukIlaniController::class);
    Route::resource('reklam-alanlari', AdminAdZoneController::class)->except(['show']);
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/disputes', [AdminDisputeController::class, 'index'])->name('disputes.index');
    Route::get('/disputes/{dispute}', [AdminDisputeController::class, 'show'])->name('disputes.show');
    Route::post('/disputes/{dispute}/resolve', [AdminDisputeController::class, 'resolve'])->name('disputes.resolve');
    Route::post('/blog/generate-ai', [AdminBlogPostController::class, 'generateAi'])->middleware('throttle:10,1')->name('blog.generate-ai');
    Route::resource('blog', AdminBlogPostController::class)->except(['show']);
    Route::resource('blog-categories', AdminBlogCategoryController::class)->except(['show']);
    Route::resource('faq', AdminFaqController::class);
    Route::get('/homepage-editor', [AdminHomepageEditorController::class, 'index'])->name('homepage-editor.index');
    Route::post('/homepage-editor', [AdminHomepageEditorController::class, 'update'])->name('homepage-editor.update');
    Route::resource('sponsors', AdminSponsorController::class)->except(['show']);
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/manual-payment', [AdminSettingController::class, 'manualPayment'])->name('settings.manual-payment');
    Route::post('/settings/mail-templates', [AdminSettingController::class, 'updateMailTemplates'])->name('settings.update-mail-templates');
    Route::post('/settings/packages', [AdminSettingController::class, 'updatePackages'])->name('settings.update-packages');
    Route::post('/settings/tool-pages', [AdminSettingController::class, 'updateToolPages'])->name('settings.tool-pages');
    Route::post('/settings/test-mail', [AdminSettingController::class, 'sendTestMail'])->name('settings.test-mail');
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [AdminNotificationController::class, 'markAllRead'])->name('notifications.read-all');
    Route::post('/notifications/destroy-all', [AdminNotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
    Route::delete('/notifications/{id}', [AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/sitemap', [\App\Http\Controllers\Admin\SitemapController::class, 'index'])->name('sitemap.index');
    Route::get('/site-contact-messages', [\App\Http\Controllers\Admin\SiteContactMessageController::class, 'index'])->name('site-contact-messages.index');
    Route::get('/site-contact-messages/{siteContactMessage}', [\App\Http\Controllers\Admin\SiteContactMessageController::class, 'show'])->name('site-contact-messages.show');
    Route::delete('/site-contact-messages/{siteContactMessage}', [\App\Http\Controllers\Admin\SiteContactMessageController::class, 'destroy'])->name('site-contact-messages.destroy');
});
