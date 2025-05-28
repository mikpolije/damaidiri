<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionnaireQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaire_questions';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id', 'id');
    }

    public function questionnaire_category()
    {
        return $this->belongsTo(QuestionnaireCategory::class, 'questionnaire_category_id', 'id');
    }

    public function screening_detail()
    {
        return $this->hasMany(ScreeningDetail::class, 'questionnaire_question_id', 'id');
    }

    public function screening_answer()
    {
        return $this->hasOne(ScreeningDetail::class, 'questionnaire_question_id', 'id');
    }
}
