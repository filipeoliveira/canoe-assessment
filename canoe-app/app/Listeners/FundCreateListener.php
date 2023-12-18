<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\FundCreateEvent;
use App\Events\DuplicateFundWarningEvent;
use App\Models\Fund;

class FundCreateListener
{
    /**
     * Handle the event.
     */
    public function handle(FundCreateEvent $event): void
    {
        $newlyCreatedFund = $event->fund;

        $timestamp = now()->toDateTimeString();
        Log::info("[Fund created][$timestamp] ID ($newlyCreatedFund->id) | name: $newlyCreatedFund->name\n");

        // Get all funds from the same manager as the newly created fund
        $fundsFromSameManager = Fund::where('manager_id', $newlyCreatedFund->manager_id)->get();

        // Check for potential duplicates
        foreach ($fundsFromSameManager as $existingFund) {
            if ($newlyCreatedFund->name === $existingFund->name) {
                event(new DuplicateFundWarningEvent($newlyCreatedFund, $existingFund));
            }
            else {
                // Check if the name matches any alias
                foreach ($existingFund->aliases as $alias) {
                    if ($newlyCreatedFund->name === $alias) {
                        event(new DuplicateFundWarningEvent($newlyCreatedFund, $existingFund));
                    }
                }
            }
        }
    }
}
