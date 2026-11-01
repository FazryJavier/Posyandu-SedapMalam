<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\Households\Pages\CreateHousehold;
use App\Filament\Resources\Households\Pages\EditHousehold;
use App\Filament\Resources\Households\RelationManagers\ResidentsRelationManager;
use App\Filament\Resources\Neighborhoods\Pages\CreateNeighborhood;
use App\Filament\Resources\PosyanduSessions\Pages\EditPosyanduSession;
use App\Filament\Resources\PosyanduSessions\RelationManagers\GrowthRecordsRelationManager as SessionGrowthRecords;
use App\Filament\Resources\Residents\Pages\CreateResident;
use App\Models\GrowthRecord;
use App\Models\Household;
use App\Models\Neighborhood;
use App\Models\PosyanduSession;
use App\Models\Resident;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Filament\Actions\CreateAction;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentFormsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);

        $admin = User::factory()->create();
        $admin->assignRole('super_admin');
        $this->actingAs($admin);
    }

    private function household(): Household
    {
        return Household::create([
            'neighborhood_id' => Neighborhood::create(['rw' => '11', 'rt' => '03'])->id,
            'address' => 'Jl. Rereng Wulung No. 11',
        ]);
    }

    public function test_admin_registers_a_neighborhood_and_a_household_per_rt(): void
    {
        Livewire::test(CreateNeighborhood::class)
            ->fillForm(['rw' => '11', 'rt' => '05', 'head_name' => 'Pak RT'])
            ->call('create')
            ->assertHasNoFormErrors();

        $neighborhood = Neighborhood::where('rt', '05')->firstOrFail();

        Livewire::test(CreateHousehold::class)
            ->fillForm([
                'neighborhood_id' => $neighborhood->id,
                'family_card_number' => '3273011111110001',
                'address' => 'Jl. Rereng Wulung No. 3',
                'has_clean_water' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('households', [
            'neighborhood_id' => $neighborhood->id,
            'family_card_number' => '3273011111110001',
            'has_clean_water' => true,
        ]);
    }

    public function test_household_form_validates_family_card_number(): void
    {
        Livewire::test(CreateHousehold::class)
            ->fillForm(['neighborhood_id' => $this->household()->neighborhood_id, 'family_card_number' => '123', 'address' => 'x'])
            ->call('create')
            ->assertHasFormErrors(['family_card_number']);
    }

    public function test_family_members_are_added_from_the_household_page(): void
    {
        $household = $this->household();

        $component = Livewire::test(ResidentsRelationManager::class, [
            'ownerRecord' => $household,
            'pageClass' => EditHousehold::class,
        ]);

        $component
            ->callAction(TestAction::make(CreateAction::class)->table(), [
                'relationship' => 'head',
                'name' => 'Budi',
                'gender' => 'male',
                'birth_date' => '1990-05-05',
                'status' => 'active',
            ])
            ->assertHasNoFormErrors();

        $component
            ->callAction(TestAction::make(CreateAction::class)->table(), [
                'relationship' => 'child',
                'name' => 'Ani',
                'gender' => 'female',
                'birth_date' => now()->subMonths(9)->toDateString(),
                'status' => 'active',
            ])
            ->assertHasNoFormErrors();

        $this->assertSame(['Budi', 'Ani'], $household->residents()->orderBy('id')->pluck('name')->all());
        $this->assertSame('Budi', $household->head->name);
    }

    public function test_resident_form_requires_a_valid_nik(): void
    {
        Livewire::test(CreateResident::class)
            ->fillForm([
                'household_id' => $this->household()->id,
                'relationship' => 'child',
                'name' => 'Ani',
                'nik' => '12345',
                'gender' => 'female',
                'status' => 'active',
            ])
            ->call('create')
            ->assertHasFormErrors(['nik']);
    }

    public function test_children_are_weighed_during_a_session_and_only_once(): void
    {
        $household = $this->household();
        $child = Resident::create([
            'household_id' => $household->id, 'name' => 'Ani', 'gender' => 'female',
            'relationship' => 'child', 'birth_date' => now()->subMonths(11),
        ]);
        $session = PosyanduSession::create(['held_on' => now()->toDateString()]);

        $component = Livewire::test(SessionGrowthRecords::class, [
            'ownerRecord' => $session,
            'pageClass' => EditPosyanduSession::class,
        ]);

        $component
            ->callAction(TestAction::make(CreateAction::class)->table(), [
                'resident_id' => $child->id,
                'weight_kg' => 9.5,
                'height_cm' => 74.0,
            ])
            ->assertHasNoFormErrors();

        $record = GrowthRecord::firstOrFail();
        $this->assertSame($session->id, $record->posyandu_session_id);
        $this->assertSame(11, $record->age_in_months);
        $this->assertEquals(round(9.5 / (0.74 ** 2), 2), (float) $record->bmi);
        $this->assertNotNull($record->measured_by);

        $component
            ->callAction(TestAction::make(CreateAction::class)->table(), [
                'resident_id' => $child->id,
                'weight_kg' => 9.9,
            ])
            ->assertHasFormErrors(['resident_id']);

        $this->assertDatabaseCount('growth_records', 1);
    }

    public function test_cms_settings_page_saves_and_updates_the_public_site(): void
    {
        Livewire::test(ManageSiteSettings::class)
            ->fillForm([
                'tagline' => 'Sehat bersama Sedap Malam',
                'schedule' => 'Setiap Jumat pertama, 08.00 WIB',
                'vision' => 'Visi hasil edit admin',
                'about' => [['text' => 'Paragraf pertama dari admin.']],
                'mission' => [['text' => 'Misi hasil edit admin']],
                'target_audience' => [['text' => 'Balita']],
                'address' => 'Alamat baru',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('Sehat bersama Sedap Malam', SiteSetting::get('tagline'));

        $this->get('/')
            ->assertOk()
            ->assertSee('Sehat bersama Sedap Malam')
            ->assertSee('Visi hasil edit admin')
            ->assertSee('Paragraf pertama dari admin.')
            ->assertSee('Setiap Jumat pertama, 08.00 WIB');
    }
}
