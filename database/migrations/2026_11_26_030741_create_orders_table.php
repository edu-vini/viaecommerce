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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // EL-8291

            // Customer Info
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('customer_cpf')->nullable();

            // Shipping Address
            $table->string('shipping_zipcode', 15);
            $table->string('shipping_address');
            $table->string('shipping_number', 30);
            $table->string('shipping_complement')->nullable();
            $table->string('shipping_neighborhood');
            $table->string('shipping_city');
            $table->string('shipping_state', 5);
            $table->string('shipping_service')->default('SEDEX');
            $table->decimal('shipping_cost', 10, 2)->default(0.00);
            $table->string('tracking_code')->nullable();

            // Payment & Totals
            $table->string('payment_method')->default('pix'); // pix, credit_card, boleto
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->string('coupon_code')->nullable();
            $table->decimal('total', 10, 2);

            // Order Status
            $table->string('status')->default('pending'); // pending, paid, preparing, shipped, delivered, cancelled
            $table->text('status_notes')->nullable();
            $table->timestamp('status_updated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
