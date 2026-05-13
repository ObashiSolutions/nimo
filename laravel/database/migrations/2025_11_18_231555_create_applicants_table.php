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

            // 1. PERSONAL DATA
            $table->string('first_name');
            $table->string('last_name');
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('email')->index();
            $table->string('phone_number');

            // 2. WORK INFORMATION
            $table->string('company_name')->nullable();
            $table->unsignedInteger('years_employed')->nullable();
            $table->string('occupation')->nullable();
            $table->string('title')->nullable();

            // 3. PROPERTY INFORMATION
            $table->string('agent_name')->nullable();
            $table->string('estate_name')->nullable();
            $table->string('property_address')->nullable();
            $table->decimal('property_cost', 15, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
