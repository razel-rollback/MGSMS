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
        Schema::create('stock_out_requests', function (Blueprint $table) {
            $table->id('stock_out_id');
            $table->unsignedBigInteger('job_order_id')->nullable();
            $table->unsignedBigInteger('job_item_id')->nullable();
            $table->unsignedBigInteger('requested_by');
            $table->timestamp('requested_at')->useCurrent();
            $table->string('status', 30)->default('pending');
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->string('note', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('job_order_id')->references('job_order_id')->on('job_orders')->onDelete('set null');
            $table->foreign('job_item_id')->references('job_item_id')->on('job_order_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_out_requests', function (Blueprint $table) {
            $table->dropForeign(['job_order_id']);
            $table->dropForeign(['job_item_id']);
        });
        Schema::dropIfExists('stock_out_requests');
    }
};
