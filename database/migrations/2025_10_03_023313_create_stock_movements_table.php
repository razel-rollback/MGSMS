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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id('movement_id');
            $table->unsignedBigInteger('item_id');
            $table->string('movement_type', 20);
            $table->string('reference_type', 60);
            $table->unsignedBigInteger('reference_id');
            $table->integer('quantity');
            $table->unsignedBigInteger('created_by');
            $table->timestamp('created_at')->useCurrent();
            $table->string('note', 255)->nullable();
            $table->foreign('item_id')->references('item_id')->on('inventory_items');
            $table->foreign('created_by')->references('employee_id')->on('employees');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->dropForeign(['created_by']);
        });
        Schema::dropIfExists('stock_movements');
    }
};
