<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comments;
use App\Models\User;
use App\Models\Posts;

class CommentController extends Controller
{
    public function store(Request $request, $post_id)
    {
        $request->validate([
            'comment_text' => 'required',
        ]);

        $post = Posts::find($post_id);
        if(!$post){
            return response()->json([
                'Message' => 'Postingan yang ada cari tidak ada!'
            ], 404);
        }

        // New Comment

        $comment = new Comments([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comment_text' => $request->input('comment_text'),
        ]);

        $comment->save();

        return response()->json([
            'mesage' => 'Komentar berhasil di tambah'
        ], 200);
    }
    public function show($post_id)
    {
        $post = Posts::find($post_id);

        if(!$post){
            return response()->json([
                'message' => 'Post tidak ditemukan' 
            ],404);
        }

        $allComments = Comments::where('post_id',$post->id)->get();

        return response()->json([
            'data' => $allComments,
        ], 200 );
    }
}
