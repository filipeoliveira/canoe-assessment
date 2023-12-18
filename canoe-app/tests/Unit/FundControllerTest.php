<?php

namespace Tests\Unit;

use App\Http\Controllers\FundController;
use App\Models\Fund;
use App\Models\FundManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FundControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index method.
     */
    public function testIndex()
    {
        $response = $this->get('api/funds');
        $response->assertStatus(200);
    }

    /**
     * Test show method with valid fund ID.
     */
    public function testShowWithValidId()
    {
        FundManager::factory()->create();
        $fund = Fund::factory()->create();
        $response = $this->get("api/funds/{$fund->id}");

        $response->assertStatus(200);
    }

    /**
     * Test show method with invalid fund ID.
     */
    public function testShowWithInvalidId()
    {
        $response = $this->get('api/funds/9999');
        $response->assertStatus(404);
    }

    /**
     * Test store method.
     */
    public function testStore()
    {
        $fundManager = FundManager::factory()->create();

        $response = $this->post('/api/funds', [
            'name' => 'New Fund',
            'fund_manager_id' => strval($fundManager->id),
            'start_year' => 2020,
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment([
            'name' => 'New Fund',
            'manager' => ['id' => $fundManager->id, 'name' => $fundManager->name],
            'start_year' => 2020,
        ]);

        // Assert the fund was created in the database
        $this->assertDatabaseHas('funds', [
            'name' => 'New Fund',
            'manager_id' => strval($fundManager->id),
            'start_year' => 2020,
        ]);
    }

    /**
     * Test update method.
     */
    public function testUpdate()
    {
        FundManager::factory(2)->create();
        $newFundManager = FundManager::factory()->create();
        $fund = Fund::factory()->create();
    
        $response = $this->put("/api/funds/{$fund->id}", [
            'name' => 'Updated Fund Name',
            'fund_manager_id' => strval($newFundManager->id),
            'start_year' => 2021,
        ]);
    
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => 'Updated Fund Name',
            'manager' => ['id' => $newFundManager->id, 'name' => $newFundManager->name],
            'start_year' => 2021,
        ]);
    
        // Assert the fund was updated in the database
        $this->assertDatabaseHas('funds', [
            'name' => 'Updated Fund Name',
            'manager_id' => $newFundManager->id,
            'start_year' => 2021,
        ]);
    }

    /**
     * Test destroy method.
     */
    public function testDestroy()
    {
        FundManager::factory()->create();
        $fund = Fund::factory()->create();
        $response = $this->delete("/api/funds/{$fund->id}");

        $response->assertStatus(204);
    }


    /**
     * Test showPossibleDuplicates method with valid fund ID.
     */
    public function testShowPossibleDuplicatesWithValidId()
    {
        FundManager::factory(1)->create();
        $fund = Fund::factory()->create();

        $response = $this->get("/api/funds/{$fund->id}/duplicates");

        $response->assertStatus(200);
    }

    /**
     * Test showPossibleDuplicates method with invalid fund ID.
     */
    public function testShowPossibleDuplicatesWithInvalidId()
    {
        $response = $this->get("/api/funds/9999/duplicates");

        $response->assertStatus(404);
    }

    /**
     * Test showPossibleDuplicates method returns possible duplicates.
     */
    public function testShowPossibleDuplicatesReturnsDuplicates()
    {
        FundManager::factory(1)->create();
        $fund = Fund::factory()->create();
        $duplicateFund = Fund::factory()->create(['name' => $fund->name]);

        $response = $this->get("/api/funds/{$fund->id}/duplicates");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $duplicateFund->id]);
    }

    /**
     * Test showPossibleDuplicates method returns empty list when no duplicates.
     */
    public function testShowPossibleDuplicatesReturnsEmptyWhenNoDuplicates()
    {
        FundManager::factory(2)->create();
        $fund = Fund::factory()->create();
        $response = $this->get("/api/funds/{$fund->id}/duplicates");

        $response->assertStatus(200);
        $response->assertJson([]);
    }
}