<?php

use App\Models\Plan;
use App\Models\User;
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
        Schema::create('user_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Plan::class)->constrained()->onDelete('cascade');
            $table->decimal('amount', 15)->default(0);
            $table->decimal('expected_profit', 15)->default(0);
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->enum('status', ['running', 'completed', 'liquidated', 'cancelled'])->default('running');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_investments');
    }
};
