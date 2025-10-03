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
        Schema::create('stock_int_items', function (Blueprint $table) {
            $table->id('sii_id');
            $table->unsignedBigInteger('stock_in_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->foreign('stock_in_id')->references('stock_in_id')->on('stock_in_requests')->onDelete('cascade');
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
        Schema::table('stock_int_items', function (Blueprint $table) {
            $table->dropForeign(['stock_in_id']);
            $table->dropForeign(['item_id']);
        });
        Schema::dropIfExists('stock_int_items');
    }
};
