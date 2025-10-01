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

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id('poi_id');
            $table->unsignedBigInteger('po_id');
            $table->unsignedBigInteger('item_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 14, 2)->storedAs('quantity * unit_price');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('po_id')->references('po_id')->on('purchase_orders')->onDelete('cascade');
            $table->foreign('item_id')->references('item_id')->on('inventory_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_order_items', function (Blueprint $table) {
            $table->dropForeign(['po_id']);
            $table->dropForeign(['item_id']);
        });
        Schema::dropIfExists('purchase_order_items');
    }
};
