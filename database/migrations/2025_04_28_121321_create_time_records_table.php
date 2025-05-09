<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeRecordsTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('time_records');
        Schema::create('time_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->enum('type', ['time_in', 'time_out']);
            $table->timestamp('recorded_at');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('time_records');
    }
}
