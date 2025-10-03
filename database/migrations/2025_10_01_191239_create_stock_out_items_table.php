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
        Schema::create('stock_out_items', function (Blueprint $table) {
            $table->id('soi_id');
            $table->unsignedBigInteger('stock_out_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->string('status', 30)->default('pending');
            $table->foreign('stock_out_id')->references('stock_out_id')->on('stock_out_requests')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('inventory_items');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_out_items', function (Blueprint $table) {
            $table->dropForeign(['stock_out_id']);
            $table->dropForeign(['item_id']);
        });
        Schema::dropIfExists('stock_out_items');
    }
};
