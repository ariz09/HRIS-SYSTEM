<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::dropIfExists('cdm_levels');
        Schema::create('cdm_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Insert default CDM levels
        DB::table('cdm_levels')->insert([
            [
                'name' => 'Management (Execom)',
                'description' => 'Executive Committee level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Consultant',
                'description' => 'Consultant level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Mancom (Expert)',
                'description' => 'Management Committee expert level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Team Lead/Supervisor (Specialist)',
                'description' => 'Team Lead or Supervisor specialist level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Advanced',
                'description' => 'Advanced professional level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Learner',
                'description' => 'Entry level or learning position',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Expanded Mancom (Specialist)',
                'description' => 'Expanded Mancom (Specialist) level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'All',
                'description' => 'All levels',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Parent/Spouse/Child',
                'description' => 'Parent/Spouse/Child level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Sibling/Grand Parent/Grandchild',
                'description' => 'Sibling/Grand Parent/Grandchild level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Cousin/Aunt/Uncle/In-law',
                'description' => 'Cousin/Aunt/Uncle/In-law level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Female Employee',
                'description' => 'Female Employee level',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('cdm_levels');
    }
};