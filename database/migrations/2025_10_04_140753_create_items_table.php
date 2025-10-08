<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
        
{
            public function up(): void
        {
            Schema::create('items', function (Blueprint $table) {
                $table->id('item_id');
                $table->string('name');
                $table->string('unit');
                $table->integer('re_order_stock')->default(0);
                $table->integer('current_stock')->default(0);
                $table->timestamps();
            });
        }
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};