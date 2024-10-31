<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\Point;

class CreateCustomerPoints
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $user = $event->user;

        Point::create([
            'customer_id' => $user->id,
            'total_points' => 0,
            'points_earned' => 0,
            'points_redeemed' => 0,
            'ranking_level' => 'Bronze',
            'last_updated' => now(),
        ]);
    }
}
