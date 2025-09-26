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
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('inventory_staff_id')->constrained('users')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('unit');
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_ins', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['inventory_staff_id']);
            $table->dropColumn(['product_id', 'supplier_id', 'inventory_staff_id', 'quantity', 'unit', 'notes']);
        });
    }
};
