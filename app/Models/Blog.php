<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'blogs';
    protected $primaryKey = 'id';
    protected $guarded = [];

    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function blog_gallery()
    {
        return $this->hasMany(BlogGallery::class, 'blog_id', 'id');
    }
}
