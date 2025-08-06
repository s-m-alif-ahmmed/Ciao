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
        Schema::create('credential_settings', function (Blueprint $table) {
            $table->id();
            $table->string('paypal_mode')->nullable();
            $table->string('paypal_client_id')->nullable();
            $table->string('paypal_client_secret_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credential_settings');
    }
};
