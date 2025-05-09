<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::dropIfExists('leave_types');

        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Insert default leave types
        DB::table('leave_types')->insert([
            [
                'code' => 'VL',
                'name' => 'Vacation Leave',
                'description' => 'Paid time off for personal reasons or travel',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'SL',
                'name' => 'Sick Leave',
                'description' => 'Paid time off for illness or medical appointments',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'BL',
                'name' => 'Birthday Leave',
                'description' => 'Paid day off on employee\'s birthday',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'EML',
                'name' => 'Expanded Maternity Leave',
                'description' => 'Extended paid leave for mothers after childbirth (105 days under Philippine law)',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'PL',
                'name' => 'Paternity Leave',
                'description' => 'Paid leave for fathers after childbirth (7 days under Philippine law)',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'ML',
                'name' => 'Marriage Leave',
                'description' => 'Paid leave for employee\'s own wedding',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'CL',
                'name' => 'Compassionate Leave',
                'description' => 'Paid leave for bereavement or family emergencies',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'SPL',
                'name' => 'Solo Parent Leave',
                'description' => 'Additional leave benefits for solo parents (7 days under Philippine law)',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'code' => 'MCL',
                'name' => 'Magna Carta Leave',
                'description' => 'Special leave for women under the Magna Carta of Women',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};