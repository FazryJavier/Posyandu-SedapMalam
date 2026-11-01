<?php

namespace Tests\Feature;

use App\Models\Cadre;
use App\Models\Child;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private function guardian(array $overrides = []): Guardian
    {
        return Guardian::create(array_merge([
            'father_name' => 'Budi',
            'mother_name' => 'Siti',
            'address' => 'Jl. Rereng Wulung No. 11',
            'phone' => '081234567890',
        ], $overrides));
    }

    private function child(Guardian $guardian, array $overrides = []): Child
    {
        return Child::create(array_merge([
            'nik' => '3273000000000001',
            'name' => 'Ani',
            'age' => 2,
            'guardian_id' => $guardian->id,
        ], $overrides));
    }

    public function test_schema_uses_english_table_and_column_names(): void
    {
        $expected = [
            'users' => ['name', 'position', 'address', 'phone', 'username', 'password'],
            'guardians' => ['father_name', 'mother_name', 'address', 'phone'],
            'children' => ['nik', 'name', 'age', 'weight', 'height', 'head_circumference', 'arm_circumference', 'bmi', 'guardian_id'],
            'cadres' => ['name', 'position', 'address', 'phone'],
            'growth_records' => ['child_nik', 'weight', 'height', 'bmi'],
        ];

        foreach ($expected as $table => $columns) {
            $this->assertTrue(Schema::hasTable($table), "Tabel {$table} tidak ada");
            $this->assertTrue(Schema::hasColumns($table, $columns), "Kolom {$table} tidak lengkap");
        }

        foreach (['data_orangtuas', 'data_anaks', 'data_kaders', 'grafik_perkembangans', 'personal_access_tokens'] as $legacy) {
            $this->assertFalse(Schema::hasTable($legacy), "Tabel lama {$legacy} masih ada");
        }
    }

    public function test_guests_are_redirected_from_admin_pages(): void
    {
        foreach (['/dashboard', '/data-orangtua', '/data-anak', '/data-kader', '/timbang-anak', '/grafik-perkembangan'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
    }

    public function test_cadre_can_register_and_log_in(): void
    {
        $this->post('/admin-registrasi-only', [
            'name' => 'Wini',
            'position' => 'Ketua',
            'address' => 'Bandung',
            'phone' => '081200000000',
            'username' => 'wini',
            'password' => 'secret123',
        ])->assertRedirect('/login');

        $user = User::where('username', 'wini')->firstOrFail();
        $this->assertSame('Wini', $user->name);
        $this->assertTrue(Hash::check('secret123', $user->password));

        $this->post('/login', ['username' => 'wini', 'password' => 'secret123'])
            ->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_validates_input_and_unique_username(): void
    {
        User::factory()->create(['username' => 'taken']);

        $this->post('/admin-registrasi-only', ['username' => 'taken', 'password' => '123'])
            ->assertSessionHasErrors(['name', 'position', 'address', 'phone', 'username', 'password']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        User::factory()->create(['username' => 'wini', 'password' => Hash::make('right-pass')]);

        $this->post('/login', ['username' => 'wini', 'password' => 'wrong'])
            ->assertSessionHas('LoginError');
        $this->assertGuest();
    }

    public function test_dashboard_shows_counts(): void
    {
        $guardian = $this->guardian();
        $this->child($guardian);
        Cadre::create(['name' => 'Wini', 'position' => 'Ketua', 'address' => 'Bandung', 'phone' => '0812']);

        $this->actingAs(User::factory()->create())
            ->get('/dashboard')
            ->assertOk()
            ->assertViewHas(['guardianCount' => 1, 'childCount' => 1, 'cadreCount' => 1]);
    }

    public function test_guardian_crud(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/data-orangtua', [
            'father_name' => 'Budi', 'mother_name' => 'Siti', 'address' => 'Bandung', 'phone' => '0812',
        ])->assertRedirect('/data-orangtua')->assertSessionHas('success');
        $guardian = Guardian::firstOrFail();

        $this->get('/data-orangtua')->assertOk()->assertSee('Budi')->assertSee('Siti');

        $this->put("/data-orangtua/{$guardian->id}", [
            'father_name' => 'Budi S', 'mother_name' => 'Siti', 'address' => 'Bandung', 'phone' => '0812',
        ])->assertRedirect('/data-orangtua');
        $this->assertSame('Budi S', $guardian->fresh()->father_name);

        $this->delete("/data-orangtua/{$guardian->id}")->assertRedirect('/data-orangtua');
        $this->assertDatabaseCount('guardians', 0);
    }

    public function test_guardian_requires_all_fields(): void
    {
        $this->actingAs(User::factory()->create())
            ->post('/data-orangtua', [])
            ->assertSessionHasErrors(['father_name', 'mother_name', 'address', 'phone']);
    }

    public function test_child_crud_and_guardian_relation(): void
    {
        $this->actingAs(User::factory()->create());
        $guardian = $this->guardian();

        $this->post('/data-anak', [
            'nik' => '3273000000000001', 'name' => 'Ani', 'age' => 2, 'weight' => 12, 'height' => 85,
            'guardian_id' => $guardian->id,
        ])->assertRedirect('/data-anak')->assertSessionHas('success');

        $child = Child::findOrFail('3273000000000001');
        $this->assertTrue($child->guardian->is($guardian));
        $this->assertTrue($guardian->children->contains($child));

        $this->get('/data-anak')->assertOk()->assertSee('Ani')->assertSee('3273000000000001');

        $this->put('/data-anak/3273000000000001', [
            'nik' => '3273000000000001', 'name' => 'Ani Putri', 'age' => 3, 'guardian_id' => $guardian->id,
        ])->assertRedirect('/data-anak');
        $this->assertSame('Ani Putri', $child->fresh()->name);

        $this->delete('/data-anak/3273000000000001')->assertRedirect('/data-anak');
        $this->assertDatabaseCount('children', 0);
    }

    public function test_child_validation_rejects_bad_input(): void
    {
        $this->actingAs(User::factory()->create());
        $guardian = $this->guardian();
        $this->child($guardian);

        $this->post('/data-anak', [
            'nik' => '3273000000000001', 'name' => 'Duplikat', 'age' => 'abc', 'guardian_id' => 999,
        ])->assertSessionHasErrors(['nik', 'age', 'guardian_id']);

        $this->post('/data-anak', [])->assertSessionHasErrors(['nik', 'name', 'age', 'guardian_id']);
    }

    public function test_deleting_guardian_cascades_to_children(): void
    {
        $this->actingAs(User::factory()->create());
        $guardian = $this->guardian();
        $this->child($guardian);

        $this->delete("/data-orangtua/{$guardian->id}")->assertRedirect('/data-orangtua');

        $this->assertDatabaseCount('children', 0);
    }

    public function test_cadre_crud(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post('/data-kader', [
            'name' => 'Wini', 'position' => 'Ketua', 'address' => 'Bandung', 'phone' => '0812',
        ])->assertRedirect('/data-kader')->assertSessionHas('success');
        $cadre = Cadre::firstOrFail();

        $this->get('/data-kader')->assertOk()->assertSee('Wini')->assertSee('Ketua');

        $this->put("/data-kader/{$cadre->id}", [
            'name' => 'Wini S', 'position' => 'Ketua', 'address' => 'Bandung', 'phone' => '0812',
        ])->assertRedirect('/data-kader');
        $this->assertSame('Wini S', $cadre->fresh()->name);

        $this->delete("/data-kader/{$cadre->id}")->assertRedirect('/data-kader');
        $this->assertDatabaseCount('cadres', 0);
    }

    public function test_weighing_and_growth_chart_pages_render(): void
    {
        $guardian = $this->guardian();
        $this->child($guardian, ['weight' => 12, 'height' => 85]);

        $this->actingAs(User::factory()->create());

        $this->get('/timbang-anak')->assertOk()->assertSee('data-weight="12"', false);
        $this->get('/grafik-perkembangan')->assertOk();
    }
}
