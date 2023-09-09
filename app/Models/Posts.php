<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'caption',
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function images()
    {
        return $this->hasMany(ImagePosts::class, 'post_id');
    }
}
