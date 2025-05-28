<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    use HasFactory;

    protected $table = 'regencies';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function profile()
    {
        return $this->hasMany(Profile::class, 'regency_id', 'id');
    }

    public function partner()
    {
        return $this->hasMany(Partner::class, 'regency_id', 'id');
    }
}
