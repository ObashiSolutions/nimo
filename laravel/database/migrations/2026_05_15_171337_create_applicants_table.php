<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {

            $table->id();

            $table->string('reference_id')->unique();

            $table->string('first_name');
            $table->string('last_name');

            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();

            $table->string('email');
            $table->string('phone_number');

            $table->string('company_name')->nullable();
            $table->unsignedInteger('years_employed')->nullable();
            $table->string('occupation')->nullable();
            $table->string('title')->nullable();

            $table->string('agent_name')->nullable();
            $table->string('estate_name')->nullable();
            $table->string('property_address')->nullable();

            $table->unsignedBigInteger('property_cost')->nullable();

            $table->string('application_status')
                ->default('Pending Payment');

            $table->string('payment_status')
                ->default('Unpaid');

            $table->string('receipt_path')->nullable();

            $table->timestamp('payment_submitted_at')
                ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};