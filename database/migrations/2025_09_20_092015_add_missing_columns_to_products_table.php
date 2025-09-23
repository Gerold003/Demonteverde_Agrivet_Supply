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
        Schema::table('products', function (Blueprint $table) {
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price_per_kilo', 8, 2)->default(0);
            $table->decimal('price_per_sack', 8, 2)->default(0);
            $table->decimal('price_per_piece', 8, 2)->default(0);
            $table->integer('current_stock_kilo')->default(0);
            $table->integer('current_stock_piece')->default(0);
            $table->integer('critical_level_kilo')->default(0);
            $table->integer('critical_level_sack')->default(0);
            $table->integer('critical_level_piece')->default(0);
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn([
                'name',
                'brand',
                'description',
                'price_per_kilo',
                'price_per_sack',
                'price_per_piece',
                'current_stock_kilo',
                'current_stock_piece',
                'critical_level_kilo',
                'critical_level_sack',
                'critical_level_piece',
                'supplier_id'
            ]);
        });
    }
};
