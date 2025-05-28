<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Response extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'responses';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function journal()
    {
        return $this->belongsTo(Journal::class, 'journal_id', 'id');
    }

    public function response_detail()
    {
        return $this->hasMany(ResponseDetail::class, 'response_id', 'id');
    }
}
