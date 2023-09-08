<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\ImagePosts;
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

        $images = ImagePosts::where('post_id', $post->id)->get();

        return response()->json([
            'data' => [
                'post' => $post,
                'images' => $images
            ]] ,200);
    }


    //Method buat post baru

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $request->validate([
            'caption' => 'required | max : 255',
            'images' => 'required | array ',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif |max:2048'
        ]);

        $user = auth()->user();

        $post = new Posts([
            'caption' => $request->caption,
            'user_id' => $user->id,
        ]);

        $post->save();
        //return hasil save 
        foreach ($request->file('images') as $image) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->storeAs('image_posts', $imageName, 'public');

            $imagePost = new ImagePosts([
                'post_id' => $post->id,
                'image_path' => $imageName
            ]);

            $imagePost->save();
        }

        return response()->json(["Message" => 'Postingan berhasil dibuat'], 201);
    }

    //Method Update Post

    public function update(Request $request, $id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return response()->json(["Message" => "Postingan tidak ditemukan"], 404);
        }

        $request->validate([
            'caption' => 'required|max:255',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // Batasan tipe dan ukuran gambar
        ]);

        $post->caption = $request->input('caption');
        $post->save();

        // Upload dan simpan gambar-gambar baru (jika ada)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->storeAs('image_posts', $imageName, 'public');

                $imagePost = new ImagePosts([
                    'post_id' => $post->id,
                    'image_path' => $imageName
                ]);

                $imagePost->save();
            }
        }

        return response()->json(["message" => "Update Postingan berhasil!"], 200);
    }

    //Method delete Post

    public function destroy($id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return response()->json(["Message" => "Postingan Tidak Ditemukan"], 404);
        }

        // Hapus semua gambar terkait
        ImagePosts::where('post_id', $post->id)->delete();

        $post->delete();

        return response()->json(["Message" => 'Postingan Berhasil dihapus'], 200);

    }
}
