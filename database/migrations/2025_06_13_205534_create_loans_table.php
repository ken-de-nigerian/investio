<?php

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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('title');
            $table->decimal('loan_amount', 15);
            $table->decimal('interest_rate', 5);
            $table->integer('tenure_months');
            $table->decimal('monthly_emi', 15)->nullable();
            $table->decimal('total_interest', 15)->nullable();
            $table->decimal('total_payment', 15)->nullable();
            $table->longText('loan_reason')->nullable();
            $table->longText('loan_collateral')->nullable();
            $table->integer('paid_emi')->nullable()->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected', 'disbursed', 'completed'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('disbursed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('next_due_date')->nullable();
            $table->timestamp('loan_end_date')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
