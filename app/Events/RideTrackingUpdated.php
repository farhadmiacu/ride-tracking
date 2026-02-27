<?php

// app/Events/RideTrackingUpdated.php
namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RideTrackingUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public int $rideId;
    public array $location;

    public function __construct(int $rideId, array $location)
    {
        $this->rideId = $rideId;
        $this->location = $location;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('ride.'.$this->rideId);
    }

    public function broadcastAs(): string
    {
        return 'tracking.update';
    }

    public function broadcastWith(): array
    {
        return $this->location;
    }
}
