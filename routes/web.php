<?php

use App\Http\Controllers\Admin\BlocklistController as AdminBlocklistController;
use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\CompanyController as AdminCompanyController;
use App\Http\Controllers\Admin\DefterReklamiController as AdminDefterReklamiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\IhaleController as AdminIhaleController;
use App\Http\Controllers\Admin\MusteriController as AdminMusteriController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\RoomTemplateController as AdminRoomTemplateController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\TeklifController as AdminTeklifController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\YukIlaniController as AdminYukIlaniController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DefterController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FirmaController;
use App\Http\Controllers\GuestWizardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IhaleController;
use App\Http\Controllers\Musteri\DashboardController as MusteriDashboardController;
use App\Http\Controllers\Musteri\IhaleController as MusteriIhaleController;
use App\Http\Controllers\Musteri\NotificationController as MusteriNotificationController;
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
use App\Http\Controllers\Nakliyeci\PaketlerController as NakliyeciPaketlerController;
use App\Http\Controllers\Nakliyeci\TeklifController as NakliyeciTeklifController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\TurkeyLocationController;
use App\Http\Controllers\WizardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Türkiye il, ilçe, mahalle API (api.turkiyeapi.dev proxy)
Route::get('/api/turkey/provinces', [TurkeyLocationController::class, 'provinces'])->name('api.turkey.provinces');
Route::get('/api/turkey/districts', [TurkeyLocationController::class, 'districts'])->name('api.turkey.districts');

Route::get('/ihaleler', [IhaleController::class, 'index'])->name('ihaleler.index');
Route::get('/ihaleler/{ihale}', [IhaleController::class, 'show'])->name('ihaleler.show');
Route::get('/nakliye-firmalari', [FirmaController::class, 'index'])->name('firmalar.index');
Route::get('/nakliye-firmalari/{company}', [FirmaController::class, 'show'])->name('firmalar.show');
Route::get('/defter', [DefterController::class, 'index'])->name('defter.index');
Route::get('/pazaryeri', [PazaryeriController::class, 'index'])->name('pazaryeri.index');
Route::get('/pazaryeri/ilan/{listing}', [PazaryeriController::class, 'show'])->name('pazaryeri.show');

Route::get('/ihale/olustur', [GuestWizardController::class, 'index'])->name('ihale.create');
Route::post('/ihale/olustur', [GuestWizardController::class, 'store'])->middleware('throttle:10,1')->name('ihale.store');

Route::get('/araclar/hacim', [ToolController::class, 'volume'])->name('tools.volume');
Route::get('/araclar/mesafe', [ToolController::class, 'distance'])->name('tools.distance');
Route::get('/araclar/tahmini-maliyet', [ToolController::class, 'cost'])->name('tools.cost');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/sss', [FaqController::class, 'index'])->name('faq.index');

Route::middleware(['guest', 'throttle:6,1'])->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/wizard', fn () => redirect()->route('ihale.create'));
    Route::post('/wizard', [GuestWizardController::class, 'store'])->middleware('throttle:10,1')->name('wizard.store');
});

Route::middleware(['auth', 'role:musteri'])->prefix('musteri')->name('musteri.')->group(function () {
    Route::get('/dashboard', [MusteriDashboardController::class, 'index'])->name('dashboard');
    Route::get('/ihaleler/{ihale}', [MusteriIhaleController::class, 'show'])->name('ihaleler.show');
    Route::post('/ihaleler/{ihale}/teklif/{teklif}/kabul', [MusteriIhaleController::class, 'acceptTeklif'])->name('ihaleler.accept-teklif');
    Route::get('/bildirimler', [MusteriNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/bildirimler/{id}/okundu', [MusteriNotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/bildirimler/okundu-tumu', [MusteriNotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
});
Route::middleware('auth')->group(function () {
    Route::get('/ihale/{ihale}/degerlendir', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/degerlendirme', [ReviewController::class, 'store'])->name('review.store');
});

Route::middleware(['auth', 'role:nakliyeci'])->prefix('nakliyeci')->name('nakliyeci.')->group(function () {
    Route::get('/dashboard', [NakliyeciDashboardController::class, 'index'])->name('dashboard');
    Route::get('/company/create', [NakliyeciCompanyController::class, 'create'])->name('company.create');
    Route::post('/company', [NakliyeciCompanyController::class, 'store'])->name('company.store');
    Route::get('/company/edit', [NakliyeciCompanyController::class, 'edit'])->name('company.edit');
    Route::put('/company', [NakliyeciCompanyController::class, 'update'])->name('company.update');
    Route::get('/teklifler', [NakliyeciTeklifController::class, 'index'])->name('teklifler.index');
    Route::get('/ledger', [NakliyeciLedgerController::class, 'index'])->name('ledger');
    Route::get('/ledger/olustur', [NakliyeciLedgerController::class, 'create'])->name('ledger.create');
    Route::post('/ledger', [NakliyeciLedgerController::class, 'store'])->name('ledger.store');
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
    Route::get('/ihaleler', [NakliyeciIhaleController::class, 'index'])->name('ihaleler.index');
    Route::get('/ihaleler/{ihale}', [NakliyeciIhaleController::class, 'show'])->name('ihaleler.show');
    Route::post('/ihaleler/teklif', [NakliyeciIhaleController::class, 'storeTeklif'])->middleware('throttle:20,1')->name('ihaleler.teklif.store');
    Route::post('/teklif', [NakliyeciTeklifController::class, 'store'])->middleware('throttle:20,1')->name('teklif.store');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'));
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::get('/musteriler', [AdminMusteriController::class, 'index'])->name('musteriler.index');
    Route::get('/musteriler/{user}', [AdminMusteriController::class, 'show'])->name('musteriler.show');
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
    Route::post('/companies/{company}/reject', [AdminCompanyController::class, 'reject'])->name('companies.reject');
    Route::get('/ihaleler', [AdminIhaleController::class, 'index'])->name('ihaleler.index');
    Route::get('/ihaleler/create', [AdminIhaleController::class, 'create'])->name('ihaleler.create');
    Route::post('/ihaleler', [AdminIhaleController::class, 'store'])->name('ihaleler.store');
    Route::get('/ihaleler/{ihale}', [AdminIhaleController::class, 'show'])->name('ihaleler.show');
    Route::get('/ihaleler/{ihale}/edit', [AdminIhaleController::class, 'edit'])->name('ihaleler.edit');
    Route::put('/ihaleler/{ihale}', [AdminIhaleController::class, 'update'])->name('ihaleler.update');
    Route::delete('/ihaleler/{ihale}', [AdminIhaleController::class, 'destroy'])->name('ihaleler.destroy');
    Route::patch('/ihaleler/{ihale}/status', [AdminIhaleController::class, 'updateStatus'])->name('ihaleler.update-status');
    Route::get('/teklifler', [AdminTeklifController::class, 'index'])->name('teklifler.index');
    Route::get('/teklifler/{teklif}/edit', [AdminTeklifController::class, 'edit'])->name('teklifler.edit');
    Route::put('/teklifler/{teklif}', [AdminTeklifController::class, 'update'])->name('teklifler.update');
    Route::delete('/teklifler/{teklif}', [AdminTeklifController::class, 'destroy'])->name('teklifler.destroy');
    Route::resource('yuk-ilanlari', AdminYukIlaniController::class);
    Route::resource('defter-reklamlari', AdminDefterReklamiController::class)->except(['show']);
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::resource('blog', AdminBlogPostController::class)->except(['show']);
    Route::resource('blog-categories', AdminBlogCategoryController::class)->except(['show']);
    Route::resource('faq', AdminFaqController::class);
    Route::resource('room-templates', AdminRoomTemplateController::class);
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/tool-pages', [AdminSettingController::class, 'updateToolPages'])->name('settings.tool-pages');
    Route::post('/settings/test-mail', [AdminSettingController::class, 'sendTestMail'])->name('settings.test-mail');
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [AdminNotificationController::class, 'markRead'])->name('notifications.read');
});
