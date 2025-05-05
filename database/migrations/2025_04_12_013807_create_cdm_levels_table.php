<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('cdm_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Insert default CDM levels
        DB::table('cdm_levels')->insert([
            [
                'name' => 'Management (Execom)',
                'code' => 'EXECOM',
                'description' => 'Executive Committee level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Consultant',
                'code' => 'CONSULT',
                'description' => 'Consultant level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mancom (Expert)',
                'code' => 'MANCOM',
                'description' => 'Management Committee expert level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Team Lead/Supervisor (Specialist)',
                'code' => 'TLSPVSR',
                'description' => 'Team Lead or Supervisor specialist level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Advanced',
                'code' => 'ADVANCED',
                'description' => 'Advanced professional level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Learner',
                'code' => 'LEARNER',
                'description' => 'Entry level or learning position',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('cdm_levels');
    }
};