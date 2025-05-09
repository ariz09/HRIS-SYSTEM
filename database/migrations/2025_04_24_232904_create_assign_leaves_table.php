<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('assign_leaves');
        
        Schema::create('assign_leaves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cdm_level_id')->nullable(); // Add the cdm_level_id field
            $table->foreign('cdm_level_id')->references('id')->on('cdm_levels')->onDelete('cascade'); // Foreign key constraint
            $table->integer('leave_count');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assign_leaves');
    }
};
