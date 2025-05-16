<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TimeRecord;
use Carbon\Carbon;

class TimeRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Find the first user (customize as needed)
        $user = User::first();
        if (!$user) {
            $this->command->warn('No user found. Please seed users first.');
            return;
        }

        // Create a sample time-in and time-out for today
        $today = Carbon::create(2025, 5, 15, 8, 0, 0); // May 15, 2025, 8:00 AM
        TimeRecord::create([
            'user_id' => $user->id,
            'type' => 'time_in',
            'recorded_at' => $today,
            'status' => 'present',
            'ip_address' => '127.0.0.1',
        ]);
        TimeRecord::create([
            'user_id' => $user->id,
            'type' => 'time_out',
            'recorded_at' => $today->copy()->addHours(9), // 5:00 PM
            'status' => 'present',
            'ip_address' => '127.0.0.1',
        ]);

        // Create 20 days of sample time-in and time-out records for the first user
        $dates = [];
        for ($i = 0; $i < 20; $i++) {
            $date = Carbon::create(2025, 4, 26)->addDays($i); // Start from April 26, 2025
            $dates[] = $date;
        }
        foreach ($dates as $date) {
            TimeRecord::create([
                'user_id' => $user->id,
                'type' => 'time_in',
                'recorded_at' => $date->copy()->setTime(8, 0, 0), // 8:00 AM
                'status' => 'present',
                'ip_address' => '127.0.0.1',
            ]);
            TimeRecord::create([
                'user_id' => $user->id,
                'type' => 'time_out',
                'recorded_at' => $date->copy()->setTime(17, 0, 0), // 5:00 PM
                'status' => 'present',
                'ip_address' => '127.0.0.1',
            ]);
        }
    }
}
