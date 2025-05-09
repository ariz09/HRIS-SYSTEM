<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable(); // Add this only if needed
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        $now = Carbon::now();

        DB::table('departments')->insert([
            ['name' => 'Management', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'HRAD', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Office of the President', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Finance', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Creatives', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Operations', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Accounts', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Digital Production', 'code' => 'DIGIPROD', 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Product Development', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Marketing', 'code' => null, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
