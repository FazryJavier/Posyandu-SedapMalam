<?php

namespace Tests\Feature;

use App\Models\Household;
use App\Models\Neighborhood;
use App\Models\Resident;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminPanelRbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolesAndPermissionsSeeder::class);
    }

    private function userWithRole(string $role, array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        $user->assignRole($role);

        return $user;
    }

    public function test_roles_are_seeded(): void
    {
        foreach (['super_admin', 'admin', 'cadre', 'content_editor'] as $role) {
            $this->assertTrue(Role::where('name', $role)->exists(), "Peran {$role} tidak ada");
        }
    }

    public function test_guests_are_sent_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/residents')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }

    public function test_user_without_a_role_cannot_open_the_panel(): void
    {
        $this->actingAs(User::factory()->create())->get('/admin')->assertForbidden();
    }

    public function test_inactive_user_cannot_open_the_panel(): void
    {
        $this->actingAs($this->userWithRole('super_admin', ['is_active' => false]))
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_super_admin_can_open_every_section(): void
    {
        $this->actingAs($this->userWithRole('super_admin'));

        foreach ([
            '/admin', '/admin/neighborhoods', '/admin/households', '/admin/residents',
            '/admin/posyandu-sessions', '/admin/cadres', '/admin/programs', '/admin/faqs',
            '/admin/gallery-items', '/admin/users', '/admin/manage-site-settings', '/admin/shield/roles',
        ] as $path) {
            $this->get($path)->assertOk();
        }
    }

    public function test_cadre_role_can_record_residents_but_cannot_touch_cms_users_or_roles(): void
    {
        $this->actingAs($this->userWithRole('cadre'));

        foreach (['/admin', '/admin/neighborhoods', '/admin/households', '/admin/residents', '/admin/posyandu-sessions', '/admin/cadres'] as $path) {
            $this->get($path)->assertOk();
        }

        foreach (['/admin/programs', '/admin/faqs', '/admin/gallery-items', '/admin/users', '/admin/manage-site-settings', '/admin/shield/roles'] as $path) {
            $this->get($path)->assertForbidden();
        }
    }

    public function test_content_editor_can_only_manage_website_content(): void
    {
        $this->actingAs($this->userWithRole('content_editor'));

        foreach (['/admin/programs', '/admin/faqs', '/admin/gallery-items', '/admin/manage-site-settings'] as $path) {
            $this->get($path)->assertOk();
        }

        foreach (['/admin/residents', '/admin/households', '/admin/posyandu-sessions', '/admin/users', '/admin/shield/roles'] as $path) {
            $this->get($path)->assertForbidden();
        }
    }

    public function test_admin_manages_data_but_not_roles(): void
    {
        $this->actingAs($this->userWithRole('admin'));

        foreach (['/admin/residents', '/admin/households', '/admin/cadres', '/admin/programs', '/admin/users', '/admin/manage-site-settings'] as $path) {
            $this->get($path)->assertOk();
        }

        $this->get('/admin/shield/roles')->assertForbidden();
    }

    public function test_permissions_are_enforced_per_action(): void
    {
        $cadre = $this->userWithRole('cadre');
        $admin = $this->userWithRole('admin');
        $super = $this->userWithRole('super_admin');

        $this->assertTrue($cadre->can('Create:Resident'));
        $this->assertFalse($cadre->can('Delete:Resident'));
        $this->assertFalse($cadre->can('ForceDelete:Resident'));
        $this->assertTrue($admin->can('Delete:Resident'));
        $this->assertFalse($admin->can('ForceDelete:Resident'));
        $this->assertTrue($super->can('ForceDelete:Resident'));
    }

    public function test_resident_edit_page_shows_the_growth_chart_for_authorized_users(): void
    {
        $household = Household::create([
            'neighborhood_id' => Neighborhood::create(['rw' => '11', 'rt' => '01'])->id,
            'address' => 'Jl. Rereng Wulung',
        ]);
        $resident = Resident::create([
            'household_id' => $household->id, 'name' => 'Ani', 'gender' => 'female',
            'relationship' => 'child', 'birth_date' => now()->subMonths(10),
        ]);

        $this->actingAs($this->userWithRole('cadre'))
            ->get("/admin/residents/{$resident->id}/edit")
            ->assertOk()
            ->assertSee('Ani');
    }
}
