<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionnaireCategoryDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questionnaire_category_details';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function questionnaire_category()
    {
        return $this->belongsTo(QuestionnaireCategory::class, 'questionnaire_category_id', 'id');
    }
}
