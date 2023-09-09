<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\ImagePosts;
use App\Models\Like;
use App\Models\Comments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class PostsController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        $post = Posts::where('user_id', $user->id)->with(['images','likes','comments'])->get();

        $post->each(function ($post) {
            $post->like_count = $post->likes->count();
            $post->comment_count = $post->comments->count();
        });
        return response()->json(['data' => $post], 200);
    }
    //Method liat post by id
    public function show(Request $request, $id)
    {
        $user = auth()->user();

        $post = Posts::where('user_id', $user->id)->find($id);

        if (!$post) {
            return response()->json(['message' => 'Postingan Tidak Ditemukan'], 404);
        }

        $images = ImagePosts::where('post_id', $post->id)->get();

        return response()->json([
            'data' => [
                'post' => $post,
                'images' => $images
            ]
        ], 200);
    }


    //Method buat post baru

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'caption' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        $user = auth()->user();

        $post = new Posts([
            'caption' => $request->caption,
            'user_id' => $user->id,
        ]);

        $post->save();

        if ($request->hasFile('image')) {
            try {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);

                // Simpan informasi gambar ke tabel image_posts
                $imagePost = new ImagePosts([
                    'post_id' => $post->id,
                    'image_path' => 'image/' . $imageName,
                ]);

                if (!$imagePost->save()) {
                    Log::error('Failed to save image data: ' . $imagePost->image_path);
                    return response()->json(['message' => 'Failed to save image data.'], 500);
                }
            } catch (\Exception $e) {
                // Tangani kesalahan saat menyimpan gambar
                return response()->json(['message' => 'Terjadi kesalahan saat menyimpan gambar.' . $e], 500);
            }
        }
        return response()->json(["message" => 'Post berhasil di buat']);
    }

    //Method Update Post

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $post = Posts::where('user_id', $user->id)->find($id);
        if (!$post) {
            return response()->json(["Message" => "Postingan tidak ditemukan"], 404);
        }

        $request->validate([
            'caption' => 'required|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        if($request->has('caption')){
            $post->caption = $request-> input('caption');
        };
        // Upload dan simpan gambar-gambar baru (jika ada)
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $image) {
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->storeAs('image_posts', $imageName, 'public');

                $imagePost = new ImagePosts([
                    'post_id' => $post->id,
                    'image_path' => $imageName
                ]);

                $imagePost->save();
            }
        }

        $post->save();

        return response()->json(["message" => "Update Postingan berhasil!"], 200);
    }


    //Method delete Post

    public function destroy(Request $request, $id)
    {
        $post = Posts::find($id);

        if (!$post) {
            return response()->json(["message" => "Postingan tidak ditemukan"], 404);
        }

        if ($post->user_id !== auth()->user()->id) {
            return response()->json(["message" => "Anda tidak memiliki izin untuk menghapus postingan ini"], 403);
        }

        // Hapus semua gambar terkait
        ImagePosts::where('post_id', $post->id)->delete();

        $post->delete();

        return response()->json(["message" => "Postingan berhasil dihapus"], 200);
    }
}
