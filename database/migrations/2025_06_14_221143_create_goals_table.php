<?php

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
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('goal_category_id')->constrained()->onDelete('restrict');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 15);
            $table->decimal('current_amount', 15)->default(0);
            $table->date('target_date');
            $table->date('start_date')->default(now());
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->boolean('is_public')->default(false);
            $table->decimal('monthly_target', 15)->nullable();
            $table->json('milestones')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
