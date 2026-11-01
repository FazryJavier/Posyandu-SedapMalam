<?php

namespace Tests\Feature;

use App\Models\Cadre;
use App\Models\Child;
use App\Models\Guardian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_renders_all_posyandu_sections(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Posyandu Sedap Malam');

        foreach (['Tentang Kami', 'Alur Pelayanan', 'Program Kerja', 'Jadwal Imunisasi', 'Tips Kesehatan Ibu dan Anak', 'Foto Kegiatan', 'Struktur Kader Posyandu', 'Pertanyaan yang Sering Diajukan', 'Lokasi dan Kontak'] as $heading) {
            $response->assertSee($heading);
        }

        $response->assertSee('Rereng Wulung');
    }

    public function test_landing_page_lists_service_tables_faq_and_immunization_rows(): void
    {
        $html = $this->get('/')->getContent();

        $this->assertSame(count(config('posyandu.meja')), substr_count($html, 'class="meja-box"'));
        $this->assertSame(count(config('posyandu.faq')), substr_count($html, 'data-bs-parent=".faq-list"'));
        $this->assertSame(count(config('posyandu.imunisasi')), substr_count($html, '<th scope="row">'));
    }

    public function test_organigram_shows_every_position_without_photos(): void
    {
        $organigram = config('posyandu.organigram');
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee($organigram['ketua']['jabatan']);
        $response->assertSee($organigram['ketua']['nama']);

        foreach (array_merge($organigram['pengurus'], $organigram['seksi']) as $posisi) {
            $response->assertSee($posisi['jabatan']);
        }

        $html = $response->getContent();
        $this->assertSame(
            1 + count($organigram['pengurus']) + count($organigram['seksi']),
            substr_count($html, 'class="org-node'),
        );
        $this->assertStringNotContainsString('assets/img/team/', $html);
    }

    public function test_landing_page_has_no_template_placeholder_text(): void
    {
        $html = $this->get('/')->getContent();

        foreach (['Lorem ipsum', 'Sarah Jhonson', 'William Anderson', 'info@example.com', 'Voluptatem'] as $placeholder) {
            $this->assertStringNotContainsString($placeholder, $html);
        }
    }

    public function test_every_local_image_on_landing_page_exists(): void
    {
        $html = $this->get('/')->getContent();

        preg_match_all('/<img[^>]+src="([^"]+)"/i', $html, $matches);
        $this->assertNotEmpty($matches[1]);

        foreach ($matches[1] as $src) {
            $path = parse_url($src, PHP_URL_PATH);
            $this->assertFileExists(public_path(ltrim($path, '/')), "Gambar tidak ditemukan: {$src}");
        }
    }

    public function test_statistics_show_live_counts_from_database(): void
    {
        $guardian = Guardian::create([
            'father_name' => 'Budi',
            'mother_name' => 'Siti',
            'address' => 'Bandung',
            'phone' => '081234567890',
        ]);
        Child::create([
            'nik' => '3273000000000001',
            'name' => 'Ani',
            'age' => 2,
            'guardian_id' => $guardian->id,
        ]);
        Cadre::create(['name' => 'Wini', 'address' => 'Bandung', 'phone' => '081200000000']);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Anak Terdata');
        $response->assertSee('Keluarga Terdaftar');
        $response->assertSee('Kader Bertugas');
        $response->assertSeeInOrder(['stat-number">1<', 'stat-number">1<', 'stat-number">1<'], false);
    }

    public function test_contact_details_are_hidden_when_not_configured(): void
    {
        config(['posyandu.telepon' => null, 'posyandu.whatsapp' => null, 'posyandu.email' => null]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('bi bi-telephone', false)
            ->assertDontSee('bi bi-envelope', false);
    }

    public function test_configured_contact_details_and_schedule_are_shown(): void
    {
        config([
            'posyandu.telepon' => '0812-3456-7890',
            'posyandu.email' => 'posyandu@example.org',
            'posyandu.jadwal' => 'Setiap Sabtu minggu ke-2, pukul 08.00 WIB',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('0812-3456-7890')
            ->assertSee('posyandu@example.org')
            ->assertSee('Setiap Sabtu minggu ke-2, pukul 08.00 WIB');
    }

    public function test_landing_page_still_renders_when_database_is_unavailable(): void
    {
        $default = config('database.default');

        config([
            'database.connections.unreachable' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'port' => 1,
                'database' => 'tidak_ada',
                'username' => 'tidak_ada',
                'password' => '',
            ],
            'database.default' => 'unreachable',
        ]);

        try {
            $this->get('/')
                ->assertOk()
                ->assertSee('Posyandu Sedap Malam')
                ->assertDontSee('Anak Terdata');
        } finally {
            config(['database.default' => $default]);
        }
    }

    public function test_login_link_points_to_login_page(): void
    {
        $this->get('/')->assertSee(url('/login'), false);
        $this->get('/login')->assertOk();
    }
}
