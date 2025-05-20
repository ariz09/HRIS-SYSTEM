<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFile201sTable extends Migration
{
    public function up()
    {
        Schema::create('file201s', function (Blueprint $table) {
            $table->id();
            $table->string('employee_number');
            $table->string('file_type');
            $table->string('attachment')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file201s');
    }
}

