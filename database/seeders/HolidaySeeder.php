<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Holiday;
use Carbon\Carbon;

class HolidaySeeder extends Seeder
{
    public function run()
    {
        $startYear = Carbon::now()->year;  // Get the current year
        $endYear = $startYear + 2;         // Add 2 years to the current year

        // Loop through the current year and the next 2 years
        for ($year = $startYear; $year <= $endYear; $year++) {
            $easter = Carbon::create($year, 3, 21)->next(Carbon::SUNDAY);  // Easter Sunday (approximate date)

            // Fixed Regular Holidays
            $holidays = [
                ['name' => 'New Year\'s Day', 'date' => Carbon::create($year, 1, 1), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'Labor Day', 'date' => Carbon::create($year, 5, 1), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'Independence Day', 'date' => Carbon::create($year, 6, 12), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'National Heroes Day', 'date' => Carbon::create($year, 8, 31)->lastOfMonth(Carbon::MONDAY), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'Bonifacio Day', 'date' => Carbon::create($year, 11, 30), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'Rizal Day', 'date' => Carbon::create($year, 12, 30), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'Christmas Day', 'date' => Carbon::create($year, 12, 25), 'status' => 'active', 'type' => 'regular'],
                ['name' => 'Feast of the Immaculate Conception of Mary', 'date' => Carbon::create($year, 12, 8), 'status' => 'active', 'type' => 'regular'],
            ];

            // Special Non-Working Holidays
            $specialHolidays = [
                ['name' => 'Ninoy Aquino Day', 'date' => Carbon::create($year, 8, 21), 'status' => 'active', 'type' => 'special'],
                ['name' => 'All Saints\' Day', 'date' => Carbon::create($year, 11, 1), 'status' => 'active', 'type' => 'special'],
                ['name' => 'All Souls\' Day', 'date' => Carbon::create($year, 11, 2), 'status' => 'active', 'type' => 'special'],
                ['name' => 'Last Day of the Year', 'date' => Carbon::create($year, 12, 31), 'status' => 'active', 'type' => 'special'],
            ];

            // Insert Fixed Regular and Special Holidays
            foreach ($holidays as $holiday) {
                Holiday::create($holiday);
            }

            foreach ($specialHolidays as $holiday) {
                Holiday::create($holiday);
            }

            // Output message for Holy Week holidays
            $this->command->info("Please manually input the Holy Week holidays (Maundy Thursday and Good Friday) for the year $year in the application.");
        }
    }
}
