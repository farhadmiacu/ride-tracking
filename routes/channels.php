<?php

use Illuminate\Support\Facades\Broadcast;

use App\Models\Ride;

Broadcast::channel('ride.{rideId}', function ($user, $rideId) {

    $ride = Ride::find($rideId);
    if (! $ride) return false;

    return $ride->driver_id === $user->id
        || $ride->rider_id === $user->id;
});
