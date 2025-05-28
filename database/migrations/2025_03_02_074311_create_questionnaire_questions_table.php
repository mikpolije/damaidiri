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
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('questionnaire_id', false, true);
            $table->bigInteger('questionnaire_category_id', false, true);
            $table->string('name');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('questionnaire_id')->references('id')->on('questionnaires')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('questionnaire_category_id')->references('id')->on('questionnaire_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_questions');
    }
};
