<?php

namespace Tests\Feature;

use App\Models\{User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReportTest extends TestCase
{
    public function test_report_screen_can_be_rendered()
    {
        $user = User::factory()->create()->assignRole(1);

        $response = $this->actingAs($user)->get(route(''));

        $response->assertStatus(200);
    }
}
