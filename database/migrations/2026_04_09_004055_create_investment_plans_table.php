<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('tier', 20)->default('BASIC');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 18, 2)->default(0.00);
            $table->decimal('target_return', 18, 2)->default(0.00);
            $table->integer('duration_days');
            $table->boolean('status')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
