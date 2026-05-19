<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applicant_tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('applicant_id')
                ->constrained('applicants')
                ->cascadeOnDelete();

            $table->string('task');
            $table->date('due_date')->nullable();
            $table->string('status')->default('Open');
            $table->string('created_by')->default('Staff');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_tasks');
    }
};