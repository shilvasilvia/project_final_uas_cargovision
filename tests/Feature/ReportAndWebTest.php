<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAndWebTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_create_country(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post('/countries', [
            'name' => 'Malaysia',
            'code' => 'MYS',
            'capital' => 'Kuala Lumpur',
            'region' => 'Southeast Asia',
            'population' => 32000000,
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('countries', ['code' => 'MYS']);
    }

    public function test_reports_page_and_pdf_export(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $responseReport = $this->actingAs($admin)->get('/reports');
        $responseReport->assertStatus(200);

        $responsePdf = $this->actingAs($admin)->get('/reports/pdf');
        $responsePdf->assertStatus(200);

        $responseExcel = $this->actingAs($admin)->get('/reports/excel');
        $responseExcel->assertStatus(200)
                      ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
