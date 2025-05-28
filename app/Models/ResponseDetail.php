<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResponseDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'response_details';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function response()
    {
        return $this->belongsTo(Response::class, 'response_id', 'id');
    }

    public function journal_question()
    {
        return $this->belongsTo(JournalQuestion::class, 'journal_question_id', 'id');
    }
}
