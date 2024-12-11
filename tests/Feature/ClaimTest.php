<?php

namespace Tests\Feature;

use App\Enums\ClaimPriorityEnum;
use App\Models\Claim;
use App\Models\ClaimItem;
use App\Models\Insurer;
use App\Models\Provider;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClaimTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_claim_with_its_items_was_created() {

        $this->assertEquals(0, Claim::count());
        $this->assertEquals(0, ClaimItem::count());


        $user = User::factory()->create();
        
        $insurer = Insurer::factory()->create();
        $provider = Provider::factory()->create();
        $specialty = Specialty::factory()->create();
        $claimItems = ClaimItem::factory()->count(5)->make();

       $this->actingAs($user)
        ->postJson('/api/claims', [
            'insurer_code' => $insurer->code,
            'provider_name' => $provider->name,
            'specialty_id' => $specialty->id,
            'encounter_date' => now()->format('Y-m-d'),
            'priority_level' => ClaimPriorityEnum::FOUR->value,
            'claim_items' => $claimItems->toArray()
        ]);

        $claim_id = Claim::first()?->id;

        $this->assertEquals(1, Claim::count());
        $this->assertEquals($claimItems->count(), ClaimItem::count());
        $this->assertDatabaseHas('claim_items', [
            'claim_id' => $claim_id
        ]);
    }

    public function test_claim_with_wrong_details_will_not_be_created() {
        $user = User::factory()->create();
        
        $insurer = Insurer::factory()->create();
        $provider = Provider::factory()->create();
        $specialty = Specialty::factory()->create();

        $response = $this->actingAs($user)
        ->postJson('/api/claims', [
            'insurer_code' => $insurer->batching_date_type,
            'provider_name' => $provider->id,
            'specialty_id' => $specialty->name,
            'encounter_date' => now()->format('Y-m-d'),
            'priority_level' => ClaimPriorityEnum::FOUR->value,
            'claim_items' => []
        ]);

        $response->assertStatus(422)
        ->assertJsonValidationErrors(['insurer_code', 'provider_name', 'specialty_id', 'claim_items']);
    }

    // test_claim_was_correctly_batched
    // test_claim_belongs_to_insurer
}
