<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\ImagePosts;
use Illuminate\Http\Request;

class ImagePostsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'required|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);
    
        $user = auth()->user();
    
        $post = new Posts([
            'caption' => $request->caption,
            'user_id' => $user->id,
        ]);
    
        $post->save();
    
        // Simpan multiple gambar ke direktori yang sesuai
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePath = $image->store('image_posts', 'public');
                $imagePost = new ImagePosts([
                    'post_id' => $post->id,
                    'image_path' => $imagePath,
                ]);
    
                $imagePost->save();
            }
        }
    
        return response()->json([
            'message' => 'Post dengan gambar berhasil dibuat',
        ]);
    }
}
