<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionnaireCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaire_categories';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class, 'questionnaire_id', 'id');
    }

    public function questionnaire_category_detail()
    {
        return $this->hasMany(QuestionnaireCategoryDetail::class, 'questionnaire_category_id', 'id');
    }

    public function questionnaire_question()
    {
        return $this->hasMany(QuestionnaireQuestion::class, 'questionnaire_category_id', 'id');
    }
}
