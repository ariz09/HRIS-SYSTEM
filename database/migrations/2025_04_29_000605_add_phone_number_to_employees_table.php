<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
        {
            Schema::table('employees', function (Blueprint $table) {
                $table->string('atm_account_number')->nullable()->after('phone_number');
            });
        }

        public function down()
        {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('atm_account_number');
            });
        }

    
};
