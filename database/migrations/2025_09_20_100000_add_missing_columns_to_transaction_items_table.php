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
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->string('unit');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total', 10, 2);

            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_items', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['transaction_id', 'product_id', 'quantity', 'unit', 'unit_price', 'total']);
        });
    }
};
