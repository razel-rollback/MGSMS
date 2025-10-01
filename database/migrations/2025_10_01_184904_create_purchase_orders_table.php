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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('po_id');
            $table->string('po_number', 60)->unique();
            $table->unsignedBigInteger('supplier_id');
            $table->timestamp('order_date')->nullable();
            $table->date('expected_date')->nullable();
            $table->decimal('total_amount', 14, 2)->default(0.00);
            $table->string('status', 30)->default('draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('created_by')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('approved_by')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('set null');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_orders', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['approved_by']);
        });
        Schema::dropIfExists('purchase_orders');
    }
};
