<?php

use App\Models\Plan;
use App\Models\UserInvestment;
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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(UserInvestment::class)->constrained()->onDelete('cascade');
            $table->foreignId('from_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 15)->default(0.00);
            $table->string('percent');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
