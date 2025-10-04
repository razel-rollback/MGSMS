<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 255); // Item name
            $table->text('description')->nullable(); // Optional description
            $table->decimal('price', 10, 2)->default(0.00); // Price
            $table->unsignedInteger('stock')->default(0); // Stock level
            $table->string('unit', 50)->default('pcs'); // Unit of measure
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};