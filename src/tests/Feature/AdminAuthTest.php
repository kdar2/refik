<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_sayfasi_render_olur(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('Hoş geldin');
    }

    public function test_admin_dashboard_yetkisiz_kullaniciyi_login_e_yonlendirir(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_basarili_giris_yapar(): void
    {
        $admin = User::factory()->create([
            'email'    => 'admin@refik.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $r = $this->post('/admin/login', [
            'email'    => 'admin@refik.test',
            'password' => 'password',
        ]);

        $r->assertRedirect('/admin');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_yanlis_sifre_giris_yapamaz(): void
    {
        User::factory()->create([
            'email'    => 'admin@refik.test',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $this->post('/admin/login', [
            'email'    => 'admin@refik.test',
            'password' => 'wrong',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_member_rolu_admin_panele_giremez(): void
    {
        User::factory()->create([
            'email'    => 'member@refik.test',
            'password' => Hash::make('password'),
            'role'     => 'member',
        ]);

        $r = $this->post('/admin/login', [
            'email'    => 'member@refik.test',
            'password' => 'password',
        ]);

        $r->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_giris_yapan_admin_dashboard_a_erisebilir(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('Toplam Bağış');
    }

    public function test_editor_rolu_admin_panele_erisebilir(): void
    {
        $editor = User::factory()->create(['role' => 'editor']);

        $this->actingAs($editor)
            ->get('/admin/posts')
            ->assertOk();
    }

    public function test_member_giris_yapip_admin_url_e_giremez(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $this->actingAs($member)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_logout_calisir(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }
}
