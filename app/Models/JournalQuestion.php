<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JournalQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'journal_questions';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id', 'id');
    }

    public function response_detail()
    {
        return $this->hasMany(ResponseDetail::class, 'journal_question_id', 'id');
    }
}
