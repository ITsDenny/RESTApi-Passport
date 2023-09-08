<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagePosts extends Model
{
    use HasFactory;

    protected $fillable = [
        'posts.id',
        'image_path',
    ];  

    public function post()
    {
        return $this->belongsTo(Posts::class);
    }
}
