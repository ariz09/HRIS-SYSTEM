<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIpAddressToTimeRecordsTable extends Migration
{
    public function up()
    {
        Schema::table('time_records', function (Blueprint $table) {
            $table->string('ip_address')->nullable(); // To store the IP address
            $table->string('location')->nullable();  // Optional, to store location (if you want)
        });
    }

    public function down()
    {
        Schema::table('time_records', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'location']);
        });
    }
}
