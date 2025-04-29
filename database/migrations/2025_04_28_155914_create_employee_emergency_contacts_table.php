<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_employee_emergency_contacts_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEmergencyContactsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('relationship');
            $table->string('mobile_number');
            $table->string('present_address');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_emergency_contacts');
    }
}
