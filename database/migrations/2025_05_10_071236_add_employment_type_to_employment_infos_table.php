<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add the employment_type_id foreign key to the employment_infos table
        Schema::table('employment_infos', function (Blueprint $table) {
            $table->foreignId('employment_type_id')->nullable()->constrained('employment_types')->onDelete('set null');
        });
    }

    public function down()
    {
        // Remove the employment_type_id foreign key from the employment_infos table
        Schema::table('employment_infos', function (Blueprint $table) {
            $table->dropForeign(['employment_type_id']);
            $table->dropColumn('employment_type_id');
        });
    }
};
