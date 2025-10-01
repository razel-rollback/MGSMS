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
        $table->id();
        $table->string('purchase_number')->unique();
        $table->string('supplier');
        $table->date('order_date');
        $table->date('delivery_date');
        $table->decimal('total_amount', 10, 2);
        $table->string('status')->default('Pending');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
