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

        Schema::create('deliveries', function (Blueprint $table) {
            $table->id('delivery_id');
            $table->string('delivery_receipt', 60)->unique();
            $table->unsignedBigInteger('po_id')->nullable();
            $table->unsignedBigInteger('supplier_id');
            $table->timestamp('delivered_date')->useCurrent();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Disapproved']);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('po_id')->references('po_id')->on('purchase_orders')->onDelete('set null');
            $table->foreign('supplier_id')->references('supplier_id')->on('suppliers')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('received_by')->references('employee_id')->on('employees')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveries');
    }
};
