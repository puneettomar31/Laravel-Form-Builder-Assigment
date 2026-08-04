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
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_id')->constrained('forms')->cascadeOnDelete();
            $table->json('submission_data');
            $table->string('search_text')->nullable();
            $table->string('user_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('stored_files')->nullable();
            $table->timestamps();

            $table->index('form_id');
            $table->index('search_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submissions');
    }
};
