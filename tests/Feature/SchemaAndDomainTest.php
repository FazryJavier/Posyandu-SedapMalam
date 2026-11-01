<?php

namespace Tests\Feature;

use App\Enums\Gender;
use App\Enums\Relationship;
use App\Models\GrowthRecord;
use App\Models\Household;
use App\Models\Neighborhood;
use App\Models\PosyanduSession;
use App\Models\Resident;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SchemaAndDomainTest extends TestCase
{
    use RefreshDatabase;

    private function household(string $rt = '01'): Household
    {
        $neighborhood = Neighborhood::firstOrCreate(['rw' => '11', 'rt' => $rt]);

        return Household::create([
            'neighborhood_id' => $neighborhood->id,
            'family_card_number' => fake()->unique()->numerify('################'),
            'address' => 'Jl. Rereng Wulung No. 11',
        ]);
    }

    private function resident(Household $household, array $attributes = []): Resident
    {
        return Resident::create(array_merge([
            'household_id' => $household->id,
            'name' => fake()->name(),
            'gender' => Gender::Female,
            'relationship' => Relationship::Child,
            'birth_date' => now()->subMonths(10),
        ], $attributes));
    }

    public function test_schema_is_normalized_and_in_english(): void
    {
        $expected = [
            'users' => ['name', 'email', 'password', 'phone', 'is_active'],
            'neighborhoods' => ['rw', 'rt', 'head_name', 'phone'],
            'households' => ['neighborhood_id', 'family_card_number', 'address', 'has_clean_water', 'has_sanitary_toilet', 'receives_social_assistance', 'deleted_at'],
            'residents' => ['household_id', 'nik', 'name', 'gender', 'birth_date', 'relationship', 'father_id', 'mother_id', 'status', 'deleted_at'],
            'posyandu_sessions' => ['held_on', 'title', 'location', 'created_by'],
            'growth_records' => ['posyandu_session_id', 'resident_id', 'measured_on', 'age_in_months', 'weight_kg', 'height_cm', 'head_circumference_cm', 'arm_circumference_cm', 'bmi', 'nutritional_status'],
            'cadres' => ['user_id', 'name', 'tier', 'position', 'is_active', 'show_on_website', 'sort_order'],
            'site_settings' => ['key', 'value'],
            'programs' => ['icon', 'title', 'description', 'is_published'],
            'faqs' => ['question', 'answer', 'is_published'],
            'gallery_items' => ['image_path', 'is_published'],
            'roles' => ['name'],
            'permissions' => ['name'],
        ];

        foreach ($expected as $table => $columns) {
            $this->assertTrue(Schema::hasTable($table), "Tabel {$table} tidak ada");
            $this->assertTrue(Schema::hasColumns($table, $columns), "Kolom {$table} tidak lengkap");
        }

        foreach (['guardians', 'children', 'data_orangtuas', 'data_anaks', 'data_kaders', 'grafik_perkembangans', 'personal_access_tokens'] as $legacy) {
            $this->assertFalse(Schema::hasTable($legacy), "Tabel lama {$legacy} masih ada");
        }
    }

    public function test_household_has_head_and_family_members(): void
    {
        $household = $this->household();
        $father = $this->resident($household, ['name' => 'Budi', 'gender' => Gender::Male, 'relationship' => Relationship::Head, 'birth_date' => '1990-01-01']);
        $mother = $this->resident($household, ['name' => 'Siti', 'relationship' => Relationship::Spouse, 'birth_date' => '1992-01-01']);
        $child = $this->resident($household, ['name' => 'Ani', 'father_id' => $father->id, 'mother_id' => $mother->id]);
        $this->resident($household, ['name' => 'Nenek', 'relationship' => Relationship::Parent, 'birth_date' => '1960-01-01']);

        $this->assertCount(4, $household->residents);
        $this->assertTrue($household->head->is($father));
        $this->assertTrue($child->father->is($father));
        $this->assertTrue($child->mother->is($mother));
        $this->assertSame('RT 01 / RW 11', $household->neighborhood->label);
        $this->assertSame(Gender::Male, $father->gender);
    }

    public function test_deleting_a_household_removes_its_members_and_soft_deletes_are_supported(): void
    {
        $household = $this->household();
        $resident = $this->resident($household);

        $resident->delete();
        $this->assertSoftDeleted($resident);

        $household->forceDelete();
        $this->assertDatabaseMissing('residents', ['id' => $resident->id]);
    }

    public function test_family_card_number_and_nik_are_unique(): void
    {
        $household = $this->household();
        $this->resident($household, ['nik' => '3273000000000001']);

        $this->expectException(QueryException::class);
        $this->resident($household, ['nik' => '3273000000000001']);
    }

    public function test_neighborhood_cannot_be_deleted_while_it_has_households(): void
    {
        $household = $this->household();

        $this->expectException(QueryException::class);
        $household->neighborhood->delete();
    }

    public function test_toddlers_scope_selects_active_children_under_five(): void
    {
        $household = $this->household();
        $baby = $this->resident($household, ['name' => 'Bayi', 'birth_date' => now()->subMonths(8)]);
        $this->resident($household, ['name' => 'Remaja', 'birth_date' => now()->subYears(14)]);
        $this->resident($household, ['name' => 'Tanpa tanggal lahir', 'birth_date' => null]);
        $this->resident($household, ['name' => 'Pindah', 'birth_date' => now()->subMonths(8), 'status' => 'moved']);

        $this->assertSame([$baby->id], Resident::toddlers()->pluck('id')->all());
    }

    public function test_growth_record_derives_age_and_bmi_and_tracks_monthly_progress(): void
    {
        $household = $this->household();
        $child = $this->resident($household, ['birth_date' => '2025-01-15']);

        foreach (['2025-07-15' => [7.5, 66.0], '2025-08-15' => [7.8, 67.5], '2025-09-15' => [8.2, 69.0]] as $date => [$weight, $height]) {
            $session = PosyanduSession::create(['held_on' => $date]);
            GrowthRecord::create([
                'posyandu_session_id' => $session->id,
                'resident_id' => $child->id,
                'measured_on' => $date,
                'weight_kg' => $weight,
                'height_cm' => $height,
            ]);
        }

        $records = $child->growthRecords;
        $this->assertCount(3, $records);
        $this->assertSame([6, 7, 8], $records->pluck('age_in_months')->all());
        $this->assertEquals(round(7.5 / (0.66 ** 2), 2), (float) $records->first()->bmi);
        $this->assertSame(['2025-07-15', '2025-08-15', '2025-09-15'], $records->map(fn ($r) => $r->measured_on->toDateString())->all());
    }

    public function test_a_child_can_only_be_measured_once_per_session(): void
    {
        $child = $this->resident($this->household());
        $session = PosyanduSession::create(['held_on' => now()]);
        $data = ['posyandu_session_id' => $session->id, 'resident_id' => $child->id, 'measured_on' => now(), 'weight_kg' => 9.0];

        GrowthRecord::create($data);

        $this->expectException(QueryException::class);
        GrowthRecord::create($data);
    }

    public function test_bmi_calculation_handles_missing_or_invalid_values(): void
    {
        $this->assertSame(16.0, GrowthRecord::calculateBmi(16, 100));
        $this->assertNull(GrowthRecord::calculateBmi(10, null));
        $this->assertNull(GrowthRecord::calculateBmi(0, 80));
        $this->assertNull(GrowthRecord::calculateBmi(10, 0));
    }
}
