<?php

namespace Tests\Feature;

use App\Models\Keluarga;
use App\Models\Kriteria;
use App\Models\Periode;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SpkPkhApplicationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

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
        if (! $user) {
            $user = User::create([
                'nama' => 'Admin Test',
                'username' => 'admin_test',
                'password' => bcrypt('admin123'),
                'jabatan' => 'Staf Pelaksana Desa',
                'role' => 'admin',
            ]);
        }

        $response = $this->withoutMiddleware(PreventRequestForgery::class)
            ->post('/login', [
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

    public function test_authenticated_user_can_view_periode_page_and_manage_periodes(): void
    {
        $user = User::first() ?? User::factory()->create(['role' => 'admin']);

        // 1. Visit index page without Undefined variable $periodeList error
        $response = $this->actingAs($user)->get(route('periode.index'));
        $response->assertStatus(200);
        $response->assertSee('Master Periode Penyaluran');

        // 2. Create new periode
        $storeResponse = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->post(route('periode.store'), [
                'nama_periode' => 'Tahap 3 Tahun 2026',
                'tahun' => 2026,
                'is_aktif' => '1',
            ]);

        $storeResponse->assertRedirect(route('periode.index'));
        $this->assertDatabaseHas('periode', [
            'nama_periode' => 'Tahap 3 Tahun 2026',
            'status' => 'aktif',
        ]);

        $newPeriode = Periode::where('nama_periode', 'Tahap 3 Tahun 2026')->first();

        // 3. Activate another periode
        $firstPeriode = Periode::where('id', '!=', $newPeriode->id)->first();
        if ($firstPeriode) {
            $activateResponse = $this->actingAs($user)
                ->withoutMiddleware(PreventRequestForgery::class)
                ->post(route('periode.activate', $firstPeriode));
            $activateResponse->assertRedirect(route('periode.index'));
            $this->assertEquals('aktif', $firstPeriode->fresh()->status);
            $this->assertEquals('arsip', $newPeriode->fresh()->status);
        }
    }

    public function test_laporan_pdf_download_returns_valid_pdf_response(): void
    {
        $user = User::first();
        $periode = Periode::where('status', 'aktif')->first() ?? Periode::first();

        $response = $this->actingAs($user)->get(route('laporan.pdf', ['periode_id' => $periode?->id]));
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_kriteria_update_bobot_successfully_updates_and_redirects(): void
    {
        $user = User::first();
        $kriterias = Kriteria::orderBy('kode')->get();

        $bobotMap = [];
        $split = 1.00 / $kriterias->count();
        foreach ($kriterias as $k) {
            $bobotMap[$k->id] = round($split, 2);
        }
        // Adjust last to equal exact 1.00
        $sum = array_sum($bobotMap);
        $lastId = $kriterias->last()->id;
        $bobotMap[$lastId] += round(1.00 - $sum, 2);

        $response = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->post(route('kriteria.update-bobot'), [
                'bobot' => $bobotMap,
            ]);

        $response->assertRedirect(route('kriteria.index'));
        $response->assertSessionHas('success');

        foreach ($bobotMap as $id => $expectedBobot) {
            $this->assertEquals((float) $expectedBobot, (float) Kriteria::find($id)->bobot);
        }
    }

    public function test_keluarga_create_and_update_lifecycle(): void
    {
        $user = User::first();

        $response = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->post(route('keluarga.store'), [
                'no_kk' => '6303010101990001',
                'nik_kepala_keluarga' => '6303010101990001',
                'nama_kepala_keluarga' => 'Ahmad Baru',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Desa RT 01 RW 01',
                'rt' => '01',
                'rw' => '01',
            ]);

        $response->assertRedirect(route('keluarga.index'));
        $this->assertDatabaseHas('keluarga', [
            'no_kk' => '6303010101990001',
            'nama_kepala_keluarga' => 'Ahmad Baru',
        ]);

        $keluarga = Keluarga::where('no_kk', '6303010101990001')->first();

        // Update
        $updateResponse = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->put(route('keluarga.update', $keluarga), [
                'no_kk' => '6303010101990001',
                'nik_kepala' => '6303010101990001',
                'nama_kepala_keluarga' => 'Ahmad Diperbarui',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Desa RT 01 RW 01 No. 5',
                'rt' => '01',
                'rw' => '01',
            ]);

        $updateResponse->assertRedirect(route('keluarga.index'));
        $this->assertDatabaseHas('keluarga', [
            'id' => $keluarga->id,
            'nama_kepala_keluarga' => 'Ahmad Diperbarui',
        ]);
    }

    public function test_penilaian_can_be_updated_and_auto_filled(): void
    {
        $user = User::first();
        $periode = Periode::where('status', 'aktif')->first();
        $keluarga = Keluarga::first();
        $kriterias = Kriteria::with('subKriteria')->get();

        // Test opening edit page before and after assessment exists
        $editResponseBefore = $this->actingAs($user)
            ->get(route('penilaian.edit', ['keluarga' => $keluarga->id, 'periode_id' => $periode->id]));
        $editResponseBefore->assertStatus(200);
        $editResponseBefore->assertSee('Input Penilaian Calon Penerima');

        $subKriteriaInput = [];
        foreach ($kriterias as $k) {
            $subKriteriaInput[$k->id] = $k->subKriteria->first()->id;
        }

        $response = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->put(route('penilaian.update', $keluarga), [
                'periode_id' => $periode->id,
                'sub_kriteria_id' => $subKriteriaInput,
            ]);

        $response->assertRedirect(route('penilaian.index', ['periode_id' => $periode->id]));

        // Test opening edit page after assessment is saved (ensures number_format string crash is resolved)
        $editResponseAfter = $this->actingAs($user)
            ->get(route('penilaian.edit', ['keluarga' => $keluarga->id, 'periode_id' => $periode->id]));
        $editResponseAfter->assertStatus(200);
        $editResponseAfter->assertSee('Input Penilaian Calon Penerima');

        // Bulk auto-fill test
        $bulkResponse = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->post(route('penilaian.bulk-auto'), [
                'periode_id' => $periode->id,
            ]);

        $bulkResponse->assertRedirect(route('penilaian.index', ['periode_id' => $periode->id]));
    }

    public function test_spk_simpan_hasil_persists_rankings(): void
    {
        $user = User::first();
        $periode = Periode::where('status', 'aktif')->first();

        $response = $this->actingAs($user)
            ->withoutMiddleware(PreventRequestForgery::class)
            ->post(route('spk.simpan'), [
                'periode_id' => $periode->id,
                'threshold' => 0.50,
            ]);

        $response->assertRedirect(route('spk.perangkingan', ['periode_id' => $periode->id]));
        $response->assertSessionHas('success');
    }
}
