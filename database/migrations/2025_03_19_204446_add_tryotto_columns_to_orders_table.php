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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tryotto_payment_status')->nullable();
            $table->string('tryotto_order_status')->nullable();
            $table->string('tryotto_transaction_ref')->nullable();
            $table->string('tryotto_payment_by')->nullable();
            $table->string('tryotto_payment_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tryotto_payment_status',
                'tryotto_order_status',
                'tryotto_transaction_ref',
                'tryotto_payment_by',
                'tryotto_payment_method'
            ]);
        });
    }
};
