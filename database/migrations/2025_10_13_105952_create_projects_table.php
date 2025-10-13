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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->foreignId('employee_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('complexity'); 
            $table->decimal('value_eur', 15, 2);
            $table->enum('status', ['in_design', 'pending', 'in_progress', 'completed'])->default('in_design');
            $table->integer('remaining_time')->nullable(); // tempo necessario/rimanente per completarlo
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
