<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionnaireAnswer extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaire_answers';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id', 'id');
    }

    public function screening_detail()
    {
        return $this->hasMany(ScreeningDetail::class, 'questionnaire_answer_id', 'id');
    }
}
