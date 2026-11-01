<?php

namespace Tests\Feature;

use App\Filament\Resources\Households\Pages\ListHouseholds;
use App\Models\GrowthRecord;
use App\Models\Household;
use App\Models\Neighborhood;
use App\Models\PosyanduSession;
use App\Models\Resident;
use App\Models\User;
use App\Support\FamilyLinkShare;
use App\Support\FamilyProgress;
use App\Support\SvgLineChart;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FamilyProgressTest extends TestCase
{
    use RefreshDatabase;

    private function household(string $card = '3273010101010001', string $phone = '081234567890'): Household
    {
        $household = Household::create([
            'neighborhood_id' => Neighborhood::firstOrCreate(['rw' => '11', 'rt' => '03'])->id,
            'family_card_number' => $card,
            'address' => 'Jl. Rahasia No. 99',
            'phone' => $phone,
        ]);
        $household->generateAccessToken();

        return $household;
    }

    private function child(Household $household, string $name, int $ageMonths = 12, array $overrides = []): Resident
    {
        return Resident::create(array_merge([
            'household_id' => $household->id,
            'name' => $name,
            'gender' => 'female',
            'relationship' => 'child',
            'birth_date' => now()->subMonths($ageMonths),
            'nik' => fake()->unique()->numerify('################'),
        ], $overrides));
    }

    /** @param  array<int, array{0: float, 1: float}>  $weights oldest first: [weight, height] */
    private function weigh(Resident $child, array $weights): void
    {
        $count = count($weights);
        foreach ($weights as $index => [$weight, $height]) {
            $date = now()->subMonths($count - 1 - $index)->startOfDay();
            $session = PosyanduSession::firstOrCreate(['held_on' => $date->toDateString()]);
            GrowthRecord::create([
                'posyandu_session_id' => $session->id,
                'resident_id' => $child->id,
                'measured_on' => $date,
                'weight_kg' => $weight,
                'height_cm' => $height,
            ]);
        }
    }

    public function test_family_sees_only_its_own_toddlers_with_measurements(): void
    {
        $household = $this->household();
        $ani = $this->child($household, 'Ani Putri', 10);
        $this->weigh($ani, [[7.0, 65.0], [7.5, 67.0], [8.0, 69.0]]);
        Resident::create(['household_id' => $household->id, 'name' => 'Budi Ayah', 'gender' => 'male', 'relationship' => 'head', 'birth_date' => '1990-01-01', 'nik' => '3273999999999999']);

        $other = $this->household('3273010101010002');
        $this->child($other, 'Anak Tetangga');

        $response = $this->get(route('family.show', $household->access_token));

        $response->assertOk()
            ->assertSee('Ani Putri')
            ->assertSee('10 bulan')
            ->assertSee('Berat badan naik 0,5 kg')
            ->assertSee('Keluarga Budi Ayah')
            ->assertDontSee('Anak Tetangga');

        // Sensitive identifiers and addresses never leave the admin panel.
        $response->assertDontSee('3273010101010001')
            ->assertDontSee('3273999999999999')
            ->assertDontSee($ani->nik)
            ->assertDontSee('Jl. Rahasia No. 99')
            ->assertDontSee('081234567890');
    }

    public function test_unknown_malformed_and_revoked_links_return_404(): void
    {
        $household = $this->household();

        $this->get(route('family.show', str_repeat('a', 40)))->assertNotFound();
        $this->get('/keluarga/terlalu-pendek')->assertNotFound();
        $this->get('/keluarga/'.str_repeat('a', 41))->assertNotFound();

        $token = $household->access_token;
        $household->revokeAccessToken();

        $this->get(route('family.show', $token))->assertNotFound();
        $this->assertNull($household->fresh()->access_token);
    }

    public function test_regenerating_invalidates_the_previous_link(): void
    {
        $household = $this->household();
        $old = $household->access_token;

        $new = $household->generateAccessToken();

        $this->assertNotSame($old, $new);
        $this->assertSame(40, strlen($new));
        $this->get(route('family.show', $old))->assertNotFound();
        $this->get(route('family.show', $new))->assertOk();
    }

    public function test_deleted_household_link_stops_working(): void
    {
        $household = $this->household();
        $token = $household->access_token;

        $household->delete();

        $this->get(route('family.show', $token))->assertNotFound();
    }

    public function test_page_is_private_uncached_and_free_of_third_party_requests(): void
    {
        $household = $this->household();
        $this->weigh($this->child($household, 'Ani'), [[7.0, 65.0], [7.4, 66.5]]);

        $response = $this->get(route('family.show', $household->access_token));

        $response->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
            ->assertHeader('Referrer-Policy', 'no-referrer');
        $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));

        $html = $response->getContent();
        $this->assertStringContainsString('noindex', $html);
        // (Livewire may inject its own same-origin script when other tests ran first, so only external scripts are forbidden.)
        $this->assertDoesNotMatchRegularExpression('/<script[^>]+src="https?:\/\/(?!localhost)/', $html);
        $this->assertDoesNotMatchRegularExpression('/(src|href)="https?:\/\/(?!localhost)/', $html);
        $this->assertStringNotContainsString('fonts.googleapis.com', $html);
    }

    public function test_viewing_records_the_last_opened_time(): void
    {
        $household = $this->household();
        $this->assertNull($household->last_viewed_at);

        $this->get(route('family.show', $household->access_token))->assertOk();

        $this->assertNotNull($household->fresh()->last_viewed_at);
    }

    public function test_only_active_toddlers_are_listed(): void
    {
        $household = $this->household();
        $this->child($household, 'Balita Aktif', 20);
        $this->child($household, 'Sudah Besar', 90);
        $this->child($household, 'Sudah Pindah', 20, ['status' => 'moved', 'nik' => null]);

        $this->get(route('family.show', $household->access_token))
            ->assertOk()
            ->assertSee('Balita Aktif')
            ->assertDontSee('Sudah Besar')
            ->assertDontSee('Sudah Pindah');
    }

    public function test_household_without_toddlers_sees_a_friendly_message(): void
    {
        $household = $this->household();

        $this->get(route('family.show', $household->access_token))
            ->assertOk()
            ->assertSee('Belum ada anak balita yang tercatat');
    }

    public function test_link_requests_are_rate_limited(): void
    {
        $household = $this->household();

        foreach (range(1, 30) as $ignored) {
            $this->get(route('family.show', $household->access_token))->assertOk();
        }

        $this->get(route('family.show', $household->access_token))->assertStatus(429);
    }

    public function test_access_token_is_never_serialized(): void
    {
        $household = $this->household();

        $this->assertArrayNotHasKey('access_token', $household->toArray());
        $this->assertStringNotContainsString($household->access_token, $household->toJson());
        $this->assertNotSame($household->access_token, $this->household('3273010101010003')->access_token);
    }

    public function test_advice_follows_the_weight_trend(): void
    {
        $make = fn (array $weights) => collect($weights)->map(fn ($w) => new GrowthRecord(['weight_kg' => $w]));

        $this->assertSame('info', FamilyProgress::advice(collect())['level']);
        $this->assertSame('info', FamilyProgress::advice($make([8.0]))['level']);
        $this->assertSame('good', FamilyProgress::advice($make([8.0, 8.4]))['level']);
        $this->assertSame('warn', FamilyProgress::advice($make([8.0, 8.0]))['level']);
        $this->assertSame('warn', FamilyProgress::advice($make([8.0, 7.6]))['level']);
        $this->assertStringContainsString('turun 0,4 kg', FamilyProgress::advice($make([8.0, 7.6]))['message']);
        $this->assertSame('alert', FamilyProgress::advice($make([8.2, 8.0, 7.9]))['level']);
        $this->assertSame('good', FamilyProgress::advice($make([8.0, 7.8, 8.3]))['level']);
    }

    public function test_age_label_is_readable(): void
    {
        $this->assertSame('-', FamilyProgress::ageLabel(null));
        $this->assertSame('8 bulan', FamilyProgress::ageLabel(8));
        $this->assertSame('2 tahun', FamilyProgress::ageLabel(24));
        $this->assertSame('1 tahun 3 bulan', FamilyProgress::ageLabel(15));
    }

    public function test_svg_chart_needs_two_points_and_stays_inside_the_canvas(): void
    {
        $this->assertNull(SvgLineChart::build([7.0], ['Jan']));
        $this->assertNull(SvgLineChart::build([7.0, null], ['Jan', 'Feb']));

        $chart = SvgLineChart::build([7.0, null, 8.0, 7.5], ['Jan', 'Feb', 'Mar', 'Apr']);

        $this->assertCount(3, $chart['dots']);
        foreach ($chart['dots'] as $dot) {
            $this->assertGreaterThanOrEqual(0, $dot['x']);
            $this->assertLessThanOrEqual(SvgLineChart::WIDTH, $dot['x']);
            $this->assertGreaterThanOrEqual(0, $dot['y']);
            $this->assertLessThanOrEqual(SvgLineChart::HEIGHT, $dot['y']);
        }
        $this->assertSame(7.0, $chart['min']);
        $this->assertSame(8.0, $chart['max']);
    }

    public function test_whatsapp_number_is_converted_to_international_format(): void
    {
        $this->assertSame('6281234567890', FamilyLinkShare::internationalNumber('0812-3456-7890'));
        $this->assertSame('6281234567890', FamilyLinkShare::internationalNumber('+62 812 3456 7890'));
        $this->assertSame('', FamilyLinkShare::internationalNumber(null));
    }

    public function test_qr_code_is_generated_locally_as_inline_svg(): void
    {
        $svg = FamilyLinkShare::qrSvg('http://localhost/keluarga/'.str_repeat('a', 40));

        $this->assertStringStartsWith('<svg', $svg);
        $this->assertStringNotContainsString('<?xml', $svg);
    }

    public function test_cadre_can_share_regenerate_and_revoke_links_from_the_admin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $cadre = User::factory()->create();
        $cadre->assignRole('cadre');
        $this->actingAs($cadre);

        $household = $this->household();
        $household->revokeAccessToken();

        $list = Livewire::test(ListHouseholds::class);

        $list->mountAction(TestAction::make('shareFamilyLink')->table($household));
        $household->refresh();
        $this->assertNotNull($household->access_token, 'Opening the share dialog creates the link');

        $url = $household->accessUrl();
        $dialog = view('filament.households.share-link', [
            'url' => $url,
            'qr' => FamilyLinkShare::qrSvg($url),
            'whatsapp' => FamilyLinkShare::whatsappUrl($household, $url),
            'hasPhone' => true,
            'lastViewed' => null,
        ])->render();
        $this->assertStringContainsString($url, $dialog);
        $this->assertStringContainsString('<svg', $dialog);
        $this->assertStringContainsString('https://wa.me/6281234567890?text=', $dialog);
        $this->assertStringContainsString('Link ini belum pernah dibuka.', $dialog);

        $first = $household->access_token;
        Livewire::test(ListHouseholds::class)
            ->callAction(TestAction::make('regenerateFamilyLink')->table($household));
        $this->assertNotSame($first, $household->fresh()->access_token);

        Livewire::test(ListHouseholds::class)
            ->callAction(TestAction::make('revokeFamilyLink')->table($household));
        $this->assertNull($household->fresh()->access_token);
    }

    public function test_content_editor_cannot_reach_family_links(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $editor = User::factory()->create();
        $editor->assignRole('content_editor');

        $this->actingAs($editor)->get('/admin/households')->assertForbidden();
    }
}
