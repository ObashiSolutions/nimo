<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('applicant_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('provider')->default('paystack');
            $table->string('reference')->unique();
            $table->integer('amount');
            $table->string('currency')->default('NGN');
            $table->string('status')->default('initialized');
            $table->text('authorization_url')->nullable();
            $table->json('provider_response')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};