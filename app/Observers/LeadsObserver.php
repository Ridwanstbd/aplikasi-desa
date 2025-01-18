<?php

namespace App\Observers;

use App\Http\Controllers\LeadsController;
use App\Models\Leads;

class LeadsObserver
{
    /**
     * Handle the Leads "created" event.
     */
    public function created(Leads $leads): void
    {
        if (app()->environment('production')) {
            app(LeadsController::class)->syncLeadToGoogleSheets($leads);
        }
    }
}
