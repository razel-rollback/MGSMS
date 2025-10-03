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
        Schema::create('job_order_items', function (Blueprint $table) {
            $table->id('job_item_id');
            $table->unsignedBigInteger('job_order_id');
            $table->unsignedBigInteger('service_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('notes', 255)->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 14, 2)->storedAs('quantity * unit_price');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('job_order_id')->references('job_order_id')->on('job_orders')->onDelete('cascade');
            $table->foreign('service_id')->references('service_id')->on('services')->onDelete('set null');
            $table->foreign('item_id')->references('item_id')->on('inventory_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_order_items', function (Blueprint $table) {
            $table->dropForeign(['job_order_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['item_id']);
        });
        Schema::dropIfExists('job_order_items');
    }
};
