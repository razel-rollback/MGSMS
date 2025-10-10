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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id('job_order_id');
            $table->string('jo_number', 60)->unique();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->timestamp('order_date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('status', 30)->default('Pending');

            $table->decimal('total_amount', 14, 2)->default(0.00);
            $table->decimal('deposit_amount', 14, 2)->default(0.00);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('created_by')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('set null');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_orders', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('job_orders');
    }
};
