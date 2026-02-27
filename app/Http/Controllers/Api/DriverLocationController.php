<?php

// app/Http/Controllers/Api/DriverLocationController.php
namespace App\Http\Controllers\Api;

use App\Events\RideTrackingUpdated;
use App\Http\Controllers\Controller;
use App\Models\Ride;
use App\Services\DistanceService;
use Illuminate\Http\Request;

class DriverLocationController extends Controller
{
    public function update(Request $request, DistanceService $distanceService)
    {
        $request->validate([
            'ride_id'  => 'required|exists:rides,id',
            'latitude' => 'required|numeric',
            'longitude'=> 'required|numeric',
        ]);

        $driver = $request->user();

        if ($driver->role !== 'driver') {
            return response()->json(['message'=>'Not driver'],403);
        }

        $ride = Ride::where('id',$request->ride_id)
            ->where('driver_id',$driver->id)
            ->whereIn('status',['accepted','ongoing'])
            ->firstOrFail();

        $newLat = (float)$request->latitude;
        $newLng = (float)$request->longitude;

        // First location save
        if (!$driver->latitude || !$driver->longitude) {
            $distance = 999;
        } else {
            $distance = $distanceService->meters(
                (float)$driver->latitude,
                (float)$driver->longitude,
                $newLat,
                $newLng
            );
        }

        if ($distance < 10) {
            return response()->json(['ignored'=>true]);
        }

        // Update driver location
        $driver->update([
            'latitude' => $newLat,
            'longitude'=> $newLng,
            'last_location_at'=> now(),
        ]);

        // Broadcast to ride channel
        broadcast(new RideTrackingUpdated(
            $ride->id,
            [
                'latitude'=>$newLat,
                'longitude'=>$newLng,
                'driver_id'=>$driver->id,
            ]
        ))->toOthers();

        return response()->json(['status'=>true]);
    }
}
