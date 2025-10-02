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
        Schema::create('iventory_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->string('name', 150);
            $table->string('unit');
            $table->integer('re_order_stock');
            $table->integer('current_stock')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('iventory_items');
    }
};
