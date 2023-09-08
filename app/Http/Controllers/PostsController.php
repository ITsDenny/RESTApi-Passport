<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Http\Request;


class PostsController extends Controller
{
    public function index()
    {
        $post = Posts::all(); //get all posts

        return response()->json(['data'=> $post], 200);
    }
    //Method liat post by id
    public function show($id)
    {
        $post = Posts::find($id); //get post by id
        if(!$post){
            return response()->json(['Message' => 'Postingan Tidak Ditemukan']);
        }

        return response()->json(['data'=> $post], 200);
    }


    //Method buat post baru

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'caption' => 'required | max : 255',
        ]);

        $user = auth()->user();

        $post = new Posts([
            'caption' => $request->caption,
            'user_id' => $user->id,
        ]);

        $post->save();
        //return hasil save 
        return response()->json([
            "Message" => 'Post berhasil di buat'
        ]);
    }

    //Method Update Post

    public function update(Request $request, $id)
    {
        $post = Posts::find($id);

        if(!$post){
            return response()->json(["Message" => "Postingan tidak ditemukan"]);
        }
        $post->caption = $request->input('caption');
        $post->save();

        return response()->json(["message"=>"Update Postingan berhasil!"], 200);
    }

    //Method delete Post

    public function destroy($id)
    {
        $post = Posts::find($id);

        if(!$post) {
            return response()->json(["Message"=>"Postingan Tidak Ditemukan"]);
        }
        $post->delete();

        return response()->json(["Message" => 'Postingan Berhasil dihapus']);
    }
}
