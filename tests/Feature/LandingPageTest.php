<?php

namespace Tests\Feature;

use App\Models\Cadre;
use App\Models\Faq;
use App\Models\GalleryItem;
use App\Models\Household;
use App\Models\Neighborhood;
use App\Models\Program;
use App\Models\Resident;
use App\Models\SiteSetting;
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
        $household = Household::create([
            'neighborhood_id' => Neighborhood::create(['rw' => '11', 'rt' => '01'])->id,
            'address' => 'Bandung',
        ]);
        Resident::create([
            'household_id' => $household->id, 'name' => 'Ani', 'gender' => 'female',
            'relationship' => 'child', 'birth_date' => now()->subMonths(14),
        ]);
        Resident::create([
            'household_id' => $household->id, 'name' => 'Budi', 'gender' => 'male',
            'relationship' => 'head', 'birth_date' => '1990-01-01',
        ]);
        Cadre::create(['name' => 'Wini', 'position' => 'Ketua', 'tier' => 'leader']);
        Cadre::create(['name' => 'Tidak aktif', 'position' => 'Kader Gizi', 'is_active' => false]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Balita Terdata');
        $response->assertSeeInOrder(['stat-number">1<', 'stat-number">1<', 'stat-number">1<'], false);
    }

    public function test_cms_settings_override_config_defaults(): void
    {
        SiteSetting::put('tagline', 'Tagline dari CMS');
        SiteSetting::put('vision', 'Visi dari CMS');
        SiteSetting::put('mission', ['Misi satu dari CMS', 'Misi dua dari CMS']);
        SiteSetting::put('schedule', 'Setiap Sabtu pertama');

        $this->get('/')
            ->assertOk()
            ->assertSee('Tagline dari CMS')
            ->assertSee('Visi dari CMS')
            ->assertSee('Misi dua dari CMS')
            ->assertSee('Setiap Sabtu pertama')
            ->assertDontSee(config('posyandu.visi'));
    }

    public function test_cms_programs_faqs_and_gallery_replace_defaults(): void
    {
        Program::create(['title' => 'Program dari CMS', 'description' => 'Deskripsi CMS', 'icon' => 'bx bx-heart', 'sort_order' => 1]);
        Program::create(['title' => 'Program Tersembunyi', 'description' => 'x', 'icon' => 'bx bx-heart', 'is_published' => false]);
        Faq::create(['question' => 'Pertanyaan dari CMS?', 'answer' => 'Jawaban CMS']);
        GalleryItem::create(['image_path' => 'gallery/uji.jpg', 'title' => 'Foto uji']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Program dari CMS')
            ->assertDontSee('Program Tersembunyi')
            ->assertDontSee('Penimbangan dan Pemantauan Tumbuh Kembang')
            ->assertSee('Pertanyaan dari CMS?')
            ->assertSee('storage/gallery/uji.jpg', false);
    }

    public function test_organigram_uses_cadres_from_the_database_per_tier(): void
    {
        Cadre::create(['name' => 'Ketua Baru', 'position' => 'Ketua Posyandu', 'tier' => 'leader']);
        Cadre::create(['name' => 'Siti Bendahara', 'position' => 'Bendahara', 'tier' => 'officer']);
        Cadre::create(['name' => 'Disembunyikan', 'position' => 'Kader Rahasia', 'tier' => 'section', 'show_on_website' => false]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Ketua Baru')
            ->assertSee('Siti Bendahara')
            ->assertDontSee('Kader Rahasia')
            ->assertSee('Kader Imunisasi');
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
                ->assertDontSee('Balita Terdata');
        } finally {
            config(['database.default' => $default]);
        }
    }

    public function test_login_link_points_to_login_page(): void
    {
        $this->get('/')->assertSee(url('/admin/login'), false);
        $this->get('/admin/login')->assertOk();
    }
}
