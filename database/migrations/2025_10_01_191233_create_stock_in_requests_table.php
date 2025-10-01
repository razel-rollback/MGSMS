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

        Schema::create('stock_in_requests', function (Blueprint $table) {
            $table->id('stock_in_id');
            $table->unsignedBigInteger('po_id')->nullable();
            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->unsignedBigInteger('requested_by');
            $table->timestamp('requested_at')->useCurrent();
            $table->string('status', 30)->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('note', 255)->nullable();
            $table->foreign('po_id')->references('po_id')->on('purchase_orders')->onDelete('set null');
            $table->foreign('delivery_id')->references('delivery_id')->on('deliveries')->onDelete('set null');
            $table->foreign('requested_by')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('approved_by')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_in_requests', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['po_id']);
            $table->dropForeign(['delivery_id']);
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['approved_by']);
        });

        Schema::dropIfExists('stock_in_requests');
    }
};
