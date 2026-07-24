<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_reports_and_create_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $responseReport = $this->actingAs($admin)->get('/reports');
        $responseReport->assertStatus(200);

        $responseCreate = $this->actingAs($admin)->post('/countries', [
            'name' => 'Germany',
            'code' => 'DEU',
            'capital' => 'Berlin',
            'region' => 'Europe',
        ]);
        $responseCreate->assertStatus(302);
        $this->assertDatabaseHas('countries', ['code' => 'DEU']);
    }

    public function test_regular_user_cannot_access_reports(): void
    {
        $regularUser = User::factory()->create(['role' => 'user']);

        $responseReport = $this->actingAs($regularUser)->get('/reports');
        $responseReport->assertStatus(403);

        $responsePdf = $this->actingAs($regularUser)->get('/reports/pdf');
        $responsePdf->assertStatus(403);
    }

    public function test_regular_user_cannot_perform_write_crud_operations(): void
    {
        $regularUser = User::factory()->create(['role' => 'user']);

        $responseCreate = $this->actingAs($regularUser)->post('/countries', [
            'name' => 'France',
            'code' => 'FRA',
        ]);

        $responseCreate->assertStatus(403);
        $this->assertDatabaseMissing('countries', ['code' => 'FRA']);
    }

    public function test_regular_user_can_read_data_and_manage_favorites(): void
    {
        $regularUser = User::factory()->create(['role' => 'user']);
        $country = Country::create(['name' => 'Italy', 'code' => 'ITA']);

        $responseIndex = $this->actingAs($regularUser)->get('/countries');
        $responseIndex->assertStatus(200);

        $responseFavorite = $this->actingAs($regularUser)->post('/favorites/toggle', [
            'type' => 'country',
            'id' => $country->id,
        ]);
        $responseFavorite->assertStatus(302);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $regularUser->id,
            'favoritable_id' => $country->id,
        ]);
    }
}
