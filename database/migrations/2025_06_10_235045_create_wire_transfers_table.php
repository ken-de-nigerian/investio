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
        Schema::create('wire_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->string('reference_id');
            $table->string('acct_name');
            $table->string('account_number');
            $table->string('bank_name');
            $table->decimal('amount', 15);
            $table->string('acct_remarks');
            $table->string('acct_type');
            $table->string('acct_country');
            $table->string('acct_swift');
            $table->string('acct_routing');
            $table->string('trans_type');
            $table->enum('trans_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wire_transfers');
    }
};
