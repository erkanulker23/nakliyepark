<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterAndAuthTest extends TestCase
{
    use RefreshDatabase;

    /** Kayıt formu first_name + last_name ile gönderildiğinde name oluşturulur ve kayıt başarılı olur. */
    public function test_register_accepts_first_name_last_name_as_name(): void
    {
        $response = $this->post(route('register'), [
            'first_name' => 'Ahmet',
            'last_name' => 'Yılmaz',
            'email' => 'ahmet@test.com',
            'phone' => '5321234567',
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
            'role' => 'musteri',
        ]);

        $response->assertRedirect(route('musteri.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'ahmet@test.com',
            'name' => 'Ahmet Yılmaz',
            'role' => 'musteri',
        ]);
    }

    /** Kayıt formu name ile gönderildiğinde aynen kabul edilir. */
    public function test_register_accepts_name_directly(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Test Kullanıcı',
            'email' => 'test@example.com',
            'password' => 'Password1',
            'password_confirmation' => 'Password1',
            'role' => 'musteri',
        ]);

        $response->assertRedirect(route('musteri.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'name' => 'Test Kullanıcı']);
    }

    /** Ana sayfa ve giriş sayfası 200 döner. */
    public function test_home_and_login_pages_load(): void
    {
        $this->get('/')->assertOk();
        $this->get(route('login'))->assertOk();
        $this->get(route('register'))->assertOk();
    }
}
