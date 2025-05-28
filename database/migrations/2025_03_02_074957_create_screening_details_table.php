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
        Schema::create('screening_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('screening_id', false, true);
            $table->bigInteger('questionnaire_question_id', false, true);
            $table->bigInteger('questionnaire_answer_id', false, true);
            $table->decimal('score', 5, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('screening_id')->references('id')->on('screenings')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('questionnaire_question_id')->references('id')->on('questionnaire_questions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('questionnaire_answer_id')->references('id')->on('questionnaire_answers')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('screening_details');
    }
};
