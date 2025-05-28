<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Journal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'journals';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function journal_question()
    {
        return $this->hasMany(JournalQuestion::class, 'journal_id', 'id');
    }

    public function response()
    {
        return $this->hasMany(Response::class, 'journal_id', 'id');
    }
}
