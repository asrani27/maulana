<?php

namespace Tests\Feature;

use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpkPkhApplicationTest extends TestCase
{
    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    public function test_login_page_renders_with_desa_branding(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Desa Thaibah Raya');
        $response->assertSee('SPK PKH - SAW');
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::where('username', 'admin')->first();
        if (!$user) {
            $user = User::create([
                'nama' => 'Admin Test',
                'username' => 'admin_test',
                'password' => bcrypt('admin123'),
                'jabatan' => 'Staf Pelaksana Desa',
                'role' => 'admin',
            ]);
        }

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_view_dashboard(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard SPK');
    }

    public function test_authenticated_user_can_view_keluarga_page(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->get('/keluarga');
        $response->assertStatus(200);
        $response->assertSee('Data Calon Penerima PKH');
    }

    public function test_authenticated_user_can_view_kriteria_and_saw_stages(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        // Kriteria
        $this->actingAs($user)->get('/kriteria')->assertStatus(200);

        // Penilaian
        $this->actingAs($user)->get('/penilaian')->assertStatus(200);

        // SPK Stages
        $this->actingAs($user)->get('/spk/normalisasi')->assertStatus(200);
        $this->actingAs($user)->get('/spk/perhitungan')->assertStatus(200);
        $this->actingAs($user)->get('/spk/perangkingan')->assertStatus(200);

        // Laporan preview
        $this->actingAs($user)->get('/laporan')->assertStatus(200);
    }

    public function test_laporan_pdf_download_returns_valid_pdf_response(): void
    {
        $user = User::first();
        $periode = Periode::where('status', 'aktif')->first() ?? Periode::first();

        $response = $this->actingAs($user)->get(route('laporan.pdf', ['periode_id' => $periode?->id]));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
