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
            'VL' => [
                ['level' => 'Learner', 'days' => 8],
                ['level' => 'Advanced', 'days' => 8],
                ['level' => 'Team Lead/Supervisor (Specialist)', 'days' => 12],
                ['level' => 'Mancom (Expert)', 'days' => 15],
                ['level' => 'Management (Execom)', 'days' => 20],
            ],
            // Sick Leave
            'SL' => [
                ['level' => 'Learner', 'days' => 7],
                ['level' => 'Advanced', 'days' => 7],
                ['level' => 'Expanded Mancom (Specialist)', 'days' => 8],
                ['level' => 'Mancom (Expert)', 'days' => 10],
                ['level' => 'Management (Execom)', 'days' => 10],
            ],
            // Birthday Leave
            'BL' => [
                ['level' => 'All', 'days' => 1],
            ],
            // Expanded Maternity Leave
            'EML' => [
                ['level' => 'All', 'days' => 105, 'type' => 'Live birth'],
                ['level' => 'All', 'days' => 60, 'type' => 'Miscarriage'],
            ],
            // Paternity Leave
            'PL' => [
                ['level' => 'All', 'days' => 7],
            ],
            // Marriage Leave
            'ML' => [
                ['level' => 'All', 'days' => 2],
            ],
            // Compassionate Leave
            'CL' => [
                ['level' => 'Parent/Spouse/Child', 'days' => 5, 'type' => 'family'],
                ['level' => 'Sibling/Grand Parent/Grandchild', 'days' => 3, 'type' => 'family'],
                ['level' => 'Cousin/Aunt/Uncle/In-law', 'days' => 2, 'type' => 'family'],
            ],
            // Solo Parent Leave
            'SPL' => [
                ['level' => 'All', 'days' => 7],
            ],
            // Magna Carta Leave
            'MCL' => [
                ['level' => 'Female Employee', 'days' => 60],
            ],
        ];

        foreach ($entitlements as $leaveTypeName => $levels) {
            $leaveType = \App\Models\LeaveType::where('code', $leaveTypeName)->first();
            if (!$leaveType) continue;

            foreach ($levels as $item) {
                $cdmLevelId = \App\Models\CdmLevel::where('name', $item['level'])->value('id');
                if ($cdmLevelId) {
                    \App\Models\LeaveEntitlement::updateOrCreate(
                        [
                            'leave_type_id' => $leaveType->id,
                            'cdm_level_id' => $cdmLevelId,
                            'type' => $item['type'] ?? null,  // Use null if no type specified
                        ],
                        [
                            'days_allowed' => $item['days'],
                        ]
                    );
                }
            }
        }
    }
}
