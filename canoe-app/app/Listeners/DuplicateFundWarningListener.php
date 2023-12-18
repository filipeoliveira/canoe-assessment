<?php

namespace App\Listeners;

use App\Events\DuplicateFundWarningEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class DuplicateFundWarningListener
{
    /**
     * Handle the event.
     */
    public function handle(DuplicateFundWarningEvent $event): void
    {
        $newlyCreatedFund = $event->newlyCreatedFund;
        $existingFund = $event->existingFund;

        $timestamp = now()->toDateTimeString();

        Log::warning("[Duplicated Fund][$timestamp]\n");
        Log::info("Newly Created Fund: {$newlyCreatedFund->name} ({$newlyCreatedFund->created_at})\n");
        Log::info("Existing Fund: {$existingFund->name} ({$existingFund->created_at})\n\n");
    }
}
