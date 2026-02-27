<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Ride;

class RideTrackingSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing test data
        Ride::truncate();
        User::whereIn('email', [
            'driver@test.com',
            'rider@test.com'
        ])->delete();

        // Create Driver
        $driver = User::create([
            'name' => 'Driver One',
            'email' => 'driver@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'driver',
            'latitude' => null,
            'longitude' => null,
            'last_location_at' => null,
        ]);

        // Create Rider
        $rider = User::create([
            'name' => 'Rider One',
            'email' => 'rider@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'rider',
        ]);

        // Create Ongoing Ride
        $ride = Ride::create([
            'driver_id' => $driver->id,
            'rider_id' => $rider->id,
            'status' => 'ongoing',
        ]);

        $this->command->info('Ride Tracking Test Data Seeded Successfully!');
        $this->command->info('Driver Email: driver@test.com');
        $this->command->info('Driver Password: 12345678');
        $this->command->info('Rider Email: rider@test.com');
        $this->command->info('Ride ID: ' . $ride->id);
    }
}
