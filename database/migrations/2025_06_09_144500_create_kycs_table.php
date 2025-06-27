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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone_number');
            $table->date('date_of_birth');

            // Identity Proof
            $table->enum('id_proof_type', ['passport', 'national_id', 'driving_license']);
            $table->string('id_front_proof_url');
            $table->string('id_back_proof_url')->nullable();

            // Address Information
            $table->string('country');
            $table->string('state');
            $table->string('city');
            $table->text('address');

            // Address Proof
            $table->enum('address_proof_type', ['passport', 'electricity_bill', 'gas_bill']);
            $table->string('address_front_proof_url');
            $table->string('address_back_proof_url')->nullable();

            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'unverified'])->default('pending');
            $table->text('rejection_reason')->nullable();

            // Timestamps
            $table->timestamps();
            $table->timestamp('approved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
