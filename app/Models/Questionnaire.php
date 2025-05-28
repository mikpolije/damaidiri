<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questionnaire extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaires';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function questionnaire_category()
    {
        return $this->hasMany(QuestionnaireCategory::class, 'questionnaire_id', 'id');
    }

    public function questionnaire_question()
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'questionnaire_id', 'id');
    }

    public function questionnaire_answer()
    {
        return $this->hasMany(QuestionnaireAnswer::class, 'questionnaire_id', 'id');
    }

    public function screening()
    {
        return $this->hasMany(Screening::class, 'questionnaire_id', 'id');
    }
}
