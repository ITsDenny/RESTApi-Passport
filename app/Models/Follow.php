<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;


class Follow extends Model
{
    use HasFactory;

    protected $table = 'user_follow';
    protected $fillable = [
        'followers_id',
        'following_id',
    ];

    // Definisikan hubungan ke User
    public function follower()
    {
        return $this->belongsTo(User::class, 'followers_id');
    }

    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }

    public function isFollowing($userToFollow)
    {
        return $this->following()->where('following_id', $userToFollow->id)->exists();
    }

}
