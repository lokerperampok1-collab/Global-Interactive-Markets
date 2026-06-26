<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('currency', 8)->default('USD');
            $table->string('type', 32);
            $table->string('status', 32)->default('pending');
            $table->decimal('amount', 18, 2)->default(0.00);
            $table->string('note')->nullable();
            $table->string('idempotency_key', 64)->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
