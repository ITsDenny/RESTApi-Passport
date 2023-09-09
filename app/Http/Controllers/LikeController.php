<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    public function like(Posts $post)
    {
        
        if (!$post->likes()->where('user_id', auth()->id())->exists()) {
            $like = new Like();
            $like->user_id = auth()->id();
            $post->likes()->save($like);
            $post->increment('like_count');
        }

        return response()->json(['message' => 'Post liked successfully'],200);
    }
    public function unlike(Posts $post)
    {
        // Pastikan pengguna sudah memberi like sebelumnya.
        $like = $post->likes()->where('user_id', auth()->id())->first();
        if ($like) {
            $like->delete();
            $post->decrement('like_count');
        }

        return response()->json(['message' => 'Post unliked successfully']);
    }
}
