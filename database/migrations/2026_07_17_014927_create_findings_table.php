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
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->string('finding_no')->unique();
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('area_id')->constrained('areas');
            $table->string('location');
            $table->text('description');
            $table->foreignId('risk_level_id')->constrained('risk_levels');
            $table->string('photo')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->dateTime('due_date');
            $table->enum('status', ['OPEN', 'IN_PROGRESS', 'WAITING_VERIFICATION', 'CLOSED', 'OVERDUE'])->default('OPEN');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
