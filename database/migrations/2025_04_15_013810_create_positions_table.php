<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('cdm_level_id')->constrained();
            $table->string('code')->unique()->nullable(); // Add the 'code' column
            $table->boolean('status')->default(1); // Add the 'status' column, defaulting to 1 (Active)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('positions');
    }
};
