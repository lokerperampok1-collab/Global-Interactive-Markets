<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('investment_plans')->cascadeOnDelete();
            $table->string('plan_name', 120);
            $table->decimal('amount', 18, 2)->default(0.00);
            $table->decimal('target_return', 18, 2)->default(0.00);
            $table->integer('duration_days')->default(0);
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('status', 20)->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_investments');
    }
};
