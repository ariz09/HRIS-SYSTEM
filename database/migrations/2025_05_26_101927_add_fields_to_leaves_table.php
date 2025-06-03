<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('type'); // Remove the old type column
            $table->foreignId('leave_type_id')->constrained('leave_types');
            $table->enum('duration', ['full_day', 'half_day_morning', 'half_day_afternoon']);
            $table->string('contact_number');
            $table->text('address_during_leave');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('type')->after('user_id');
            $table->dropForeign(['leave_type_id']);
            $table->dropColumn('leave_type_id');
            $table->dropColumn('duration');
            $table->dropColumn('contact_number');
            $table->dropColumn('address_during_leave');
        });
    }
};
