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
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id('adjustment_id');
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('requested_by');
            $table->timestamp('requested_at')->useCurrent();
            $table->string('status', 30)->default('Pending');
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('adjustment_type', 30);
            $table->integer('quantity');
            $table->string('reason', 255);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('item_id')->references('item_id')->on('inventory_items');
            $table->foreign('requested_by')->references('employee_id')->on('employees');
            $table->foreign('approved_by')->references('employee_id')->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['requested_by']);
            $table->dropForeign(['approved_by']);
        });
        Schema::dropIfExists('stock_adjustments');
    }
};
