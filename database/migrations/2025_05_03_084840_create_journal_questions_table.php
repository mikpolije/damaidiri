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
        Schema::create('journal_questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('journal_id', false, true);
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('journal_id')->references('id')->on('journals')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_questions');
    }
};
