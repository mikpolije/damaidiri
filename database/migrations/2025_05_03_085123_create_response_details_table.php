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
        Schema::create('response_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('response_id', false, true);
            $table->bigInteger('journal_question_id', false, true);
            $table->longText('answer');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('response_id')->references('id')->on('responses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('journal_question_id')->references('id')->on('journal_questions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_details');
    }
};
