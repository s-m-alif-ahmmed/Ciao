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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('shop_id')->nullable()->constrained('shops')->onDelete('cascade');
            $table->foreignId('valet_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('payment_method', ['paypal', 'cash'])->nullable();
            $table->string('payment_id')->nullable();
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->integer('tax_percentage')->nullable();
            $table->decimal('tax', 10, 2)->default(0.00);
            $table->decimal('valet_charge', 10, 2)->default(2.00);
            $table->decimal('valet_tip', 10, 2)->default(0.00);
            $table->decimal('platform_fee', 10, 2)->default(1.00);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('not_found_total', 10, 2);
            $table->decimal('total_price', 10, 2); // total price (sub_total - discount + tax + valet_charge + platform_fee)
            $table->enum('shopping_payment', ['paid','unpaid'])->default('unpaid');
            $table->enum('valet_payment', ['paid','unpaid'])->default('unpaid');
            $table->enum('payment_status', ['paid','unpaid'])->default('unpaid');
            $table->enum('status', ['pending','completed', 'cancelled'])->default('pending');
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
