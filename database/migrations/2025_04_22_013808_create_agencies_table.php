<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        Schema::create('agencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // Insert default agencies
        DB::table('agencies')->insert([
            [
                'name' => 'Tagline Communications, Inc.',
                'code' => 'TAG',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '5Seconds Advertising, Inc.',
                'code' => '5SEC',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Oncue Media Inc.',
                'code' => 'ONCUE',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Hey Communications Inc.',
                'code' => 'HEY',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Red Button Inc.',
                'code' => 'REDBTN',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Binary Digital Advertising Inc.',
                'code' => 'BINARY',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Tagline 360',
                'code' => 'TAG360',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Adept Staffing Solutions',
                'code' => 'ADEPT',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('agencies');
    }
};