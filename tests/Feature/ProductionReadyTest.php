<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Ihale;
use App\Models\Teklif;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductionReadyTest extends TestCase
{
    use RefreshDatabase;

    /** Doğrulanmamış müşteri musteri paneline erişemez, verification.notice'e yönlendirilir. */
    public function test_unverified_musteri_redirected_to_verification_notice(): void
    {
        $user = User::factory()->create([
            'role' => 'musteri',
            'email_verified_at' => null,
        ]);
        $this->actingAs($user)
            ->get(route('musteri.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    /** Doğrulanmamış nakliyeci nakliyeci paneline erişemez. */
    public function test_unverified_nakliyeci_redirected_to_verification_notice(): void
    {
        $user = User::factory()->create([
            'role' => 'nakliyeci',
            'email_verified_at' => null,
        ]);
        Company::create([
            'user_id' => $user->id,
            'name' => 'Test Firma',
            'approved_at' => now(),
        ]);
        $this->actingAs($user)
            ->get(route('nakliyeci.dashboard'))
            ->assertRedirect(route('verification.notice'));
    }

    /** Admin e-posta doğrulama olmadan panele erişebilir. */
    public function test_admin_can_access_without_email_verification(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email_verified_at' => null,
        ]);
        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    /** Admin ihale closed yaptığında kabul edilmemiş teklifler rejected olur. */
    public function test_admin_ihale_closed_rejects_pending_teklifler(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email_verified_at' => now()]);
        $musteri = User::factory()->create(['role' => 'musteri', 'email_verified_at' => now()]);
        $company = Company::create(['user_id' => User::factory()->create(['role' => 'nakliyeci'])->id, 'name' => 'Firma', 'approved_at' => now()]);

        $ihale = Ihale::create([
            'user_id' => $musteri->id,
            'from_city' => 'İstanbul',
            'to_city' => 'Ankara',
            'status' => 'published',
            'service_type' => 'evden_eve_nakliyat',
        ]);
        $t1 = Teklif::create(['ihale_id' => $ihale->id, 'company_id' => $company->id, 'amount' => 1000, 'status' => 'pending']);
        $t2 = Teklif::create(['ihale_id' => $ihale->id, 'company_id' => Company::create(['user_id' => User::factory()->create()->id, 'name' => 'F2', 'approved_at' => now()])->id, 'amount' => 2000, 'status' => 'pending']);

        $this->actingAs($admin)
            ->patch(route('admin.ihaleler.update-status', $ihale), ['status' => 'closed'])
            ->assertRedirect();

        $ihale->refresh();
        $this->assertSame('closed', $ihale->status);
        $this->assertSame('rejected', $t1->fresh()->status);
        $this->assertSame('rejected', $t2->fresh()->status);
    }

    /** BlockedPhone tek sorgu ile engel kontrolü (normalized_phone). */
    public function test_blocked_phone_normalized_query(): void
    {
        \App\Models\BlockedPhone::create(['phone' => '+90 555 123 45 67', 'reason' => 'test']);
        $this->assertTrue(\App\Models\BlockedPhone::isBlocked('0555 123 45 67'));
        $this->assertTrue(\App\Models\BlockedPhone::isBlocked('905551234567'));
        $this->assertFalse(\App\Models\BlockedPhone::isBlocked('0555 999 99 99'));
    }
}
