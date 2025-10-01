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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('employee_id');
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('phone', 30)->nullable();
            $table->string('email', 120)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop foreign key constraints
            // Replace 'employees_department_id_foreign' with the actual foreign key name
            // or use the column name in an array if Laravel automatically named it.
            $table->dropForeign(['department_id']); // Example for a 'department_id' foreign key
            // Add other dropForeign calls for any other foreign keys on the 'employees' table
        });
        Schema::dropIfExists('employees');
    }
};
