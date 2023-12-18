<?php

namespace Tests\Unit;

use App\Events\DuplicateFundWarningEvent;
use App\Listeners\DuplicateFundWarningListener;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Tests\TestCase;
use App\Models\Fund;

class DuplicateFundWarningListenerTest extends TestCase
{
    /**
     * Test handle method logs correct messages.
     */
    public function testHandleLogsCorrectMessages()
        {
        // Arrange
        config(['logging.default' => 'test']); // Switch to the test log channel
        $newlyCreatedFund = new Fund(['name' => 'New Fund', 'created_at' => now()]);
        $existingFund = new Fund(['name' => 'Existing Fund', 'created_at' => now()->subDay()]);

        $event = new DuplicateFundWarningEvent($newlyCreatedFund, $existingFund);

        // Act
        $listener = new DuplicateFundWarningListener();
        $listener->handle($event);

        // Assert
        $logContent = file_get_contents(storage_path('logs/laravel-test.log'));

        $this->assertStringContainsString('[Duplicated Fund]', $logContent);
        $this->assertStringContainsString("Newly Created Fund: {$newlyCreatedFund->name}", $logContent);
        $this->assertStringContainsString("Existing Fund: {$existingFund->name}", $logContent);
    }
}