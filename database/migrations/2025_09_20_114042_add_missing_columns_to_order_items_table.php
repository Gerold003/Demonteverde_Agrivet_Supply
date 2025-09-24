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
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'order_id')) {
                $table->unsignedBigInteger('order_id');
                $table->foreign('order_id')->references('id')->on('orders');
            }

            if (!Schema::hasColumn('order_items', 'product_id')) {
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products');
            }

            if (!Schema::hasColumn('order_items', 'quantity')) {
                $table->integer('quantity');
            }

            if (!Schema::hasColumn('order_items', 'unit')) {
                $table->string('unit');
            }

            if (!Schema::hasColumn('order_items', 'unit_price')) {
                $table->decimal('unit_price', 10, 2);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['order_id', 'product_id', 'quantity', 'unit', 'unit_price']);
        });
    }
};
