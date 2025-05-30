<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;
use App\Models\LeaveEntitlement;
use App\Models\CdmLevel;

class LeaveEntitlementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Map of leave type names to their entitlement data
        $entitlements = [
            // Vacation Leave
            'Vacation Leave' => [
                ['level' => 'Learner', 'days' => 8],
                ['level' => 'Advanced', 'days' => 8],
                ['level' => 'Team Lead/Supervisor (Specialist)', 'days' => 12],
                ['level' => 'Mancom (Expert)', 'days' => 15],
                ['level' => 'Management (Execcom)', 'days' => 20],
            ],
                // // Sick Leave
                // 'Sick Leave' => [
                //     ['level' => 'Learner and Advanced', 'days' => 7],
                //     ['level' => 'Expanded Mancom (Specialist)', 'days' => 8],
                //     ['level' => 'Mancom (Expert)', 'days' => 10],
                //     ['level' => 'Management (Execcom)', 'days' => 10],
                // ],
                // // Birthday Leave
                // 'Birthday Leave' => [
                //     ['level' => 'All', 'days' => 1],
                // ],
                // // Expanded Maternity Leave
                // 'Expanded Maternity Leave' => [
                //     ['level' => 'All', 'days' => 105], // Live birth
                //     ['level' => 'All', 'days' => 60],  // Miscarriage
                // ],
                // // Paternity Leave
                // 'Paternity Leave' => [
                //     ['level' => 'All', 'days' => 7],
                // ],
                // // Marriage Leave
                // 'Marriage Leave' => [
                //     ['level' => 'All', 'days' => 2],
                // ],
                // // Compassionate Leave
                // 'Compassionate Leave' => [
                //     ['level' => 'Parent/Spouse/Child', 'days' => 5],
                //     ['level' => 'Sibling/Grand Parent/Grandchild', 'days' => 3],
                //     ['level' => 'Cousin/Aunt/Uncle/In-law', 'days' => 2],
                // ],
        ];

        foreach ($entitlements as $leaveTypeName => $levels) {
            $leaveType = LeaveType::where('name', $leaveTypeName)->first();
            if (!$leaveType) continue;
            foreach ($levels as $item) {
                $cdmLevelId = CdmLevel::where('name', $item['level'])->value('id');
                if ($cdmLevelId) {
                    LeaveEntitlement::updateOrCreate([
                        'leave_type_id' => $leaveType->id,
                        'cdm_level_id' => $cdmLevelId,
                    ], [
                        'days_allowed' => $item['days'],
                    ]);
                }
            }
        }
    }
}
