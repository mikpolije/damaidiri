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
        Schema::create('questionnaire_category_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('questionnaire_category_id', false, true);
            $table->string('level');
            $table->integer('minimum_score')->unsigned();
            $table->integer('maximum_score')->unsigned()->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('questionnaire_category_id')->references('id')->on('questionnaire_categories')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_category_details');
    }
};
