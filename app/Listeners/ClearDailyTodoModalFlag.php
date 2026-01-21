<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class ClearDailyTodoModalFlag
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Ensure the daily todo modal flag is cleared on fresh login
        // so the modal can be shown for the new session.
        try {
            session()->forget('daily_todo_modal_shown');
        } catch (\Throwable $e) {
            // ignore session errors if any (shouldn't block login)
        }
    }
}
