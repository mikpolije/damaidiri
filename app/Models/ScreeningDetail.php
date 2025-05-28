<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScreeningDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'screening_details';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function screening()
    {
        return $this->belongsTo(Screening::class, 'screening_id', 'id');
    }

    public function questionnaire_question()
    {
        return $this->belongsTo(QuestionnaireQuestion::class, 'questionnaire_question_id', 'id');
    }

    public function questionnaire_answer()
    {
        return $this->belongsTo(QuestionnaireAnswer::class, 'questionnaire_answer_id', 'id');
    }
}
