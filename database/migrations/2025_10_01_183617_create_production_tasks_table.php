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

        Schema::create('production_tasks', function (Blueprint $table) {
            $table->id('task_id');
            $table->unsignedBigInteger('job_item_id');
            $table->string('task_type', 50);
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('status', 30)->default('assigned');
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->foreign('job_item_id')->references('job_item_id')->on('job_order_items')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('assigned_to')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('set null');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('production_tasks', function (Blueprint $table) {
            $table->dropForeign(['job_item_id']);
            $table->dropForeign(['assigned_to']);
        });
        Schema::dropIfExists('production_tasks');
    }
};
