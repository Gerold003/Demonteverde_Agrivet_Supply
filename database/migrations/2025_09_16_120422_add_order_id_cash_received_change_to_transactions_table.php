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
        Schema::table('transactions', function (Blueprint $table) {
            // First check if columns already exist to avoid errors
            if (!Schema::hasColumn('transactions', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable();
                $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            }
            
            if (!Schema::hasColumn('transactions', 'cash_received')) {
                $table->decimal('cash_received', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('transactions', 'change')) {
                $table->decimal('change', 10, 2)->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Check if foreign key exists before trying to drop it
            if (Schema::hasColumn('transactions', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
            
            if (Schema::hasColumn('transactions', 'cash_received')) {
                $table->dropColumn('cash_received');
            }
            
            if (Schema::hasColumn('transactions', 'change')) {
                $table->dropColumn('change');
            }
        });
    }
};