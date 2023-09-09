<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FollowController extends Controller
{
    public function followUser(Request $request, $user_id)
    {
        // Temukan pengguna yang akan diikuti
        $userToFollow = User::find($user_id);

        if (!$userToFollow) {
            return response()->json(['message' => 'Pengguna yang akan diikuti tidak ditemukan'], 404);
        }

        $user = auth()->user();

        // Cek apakah pengguna yang sedang login sudah mengikuti pengguna yang akan diikuti
        $follow = Follow::where('followers_id', $user->id)
            ->where('following_id', $userToFollow->id)
            ->first();

        if ($follow) {
            // Sudah mengikuti pengguna
            return response()->json(['message' => 'Anda sudah mengikuti akun ini!'], 400);
        }

        // Jika belum mengikuti, buat hubungan follow
        $follow = new Follow([
            'followers_id' => $user->id,
            'following_id' => $userToFollow->id,
        ]);
        $follow->save();

        return response()->json(['message' => 'Berhasil mengikuti'], 200);
    }

    public function unfollowUser(Request $request, $user_id)
    {
        // Temukan pengguna yang akan di-unfollow
        $userToUnfollow = User::find($user_id);

        if (!$userToUnfollow) {
            return response()->json(['message' => 'Pengguna yang ingin di-unfollow tidak ditemukan'], 404);
        }

        $user = auth()->user();

        // Temukan hubungan follow antara pengguna yang login dan yang akan di-unfollow
        $follow = Follow::where('followers_id', $user->id)
            ->where('following_id', $userToUnfollow->id)
            ->first();

        if (!$follow) {
            // Pengguna tidak sedang mengikuti pengguna yang ingin di-unfollow
            return response()->json(['message' => 'Anda belum mengikuti pengguna ini'], 400);
        }

        // Hapus hubungan 'follow'
        $follow->delete();

        return response()->json(['message' => 'Anda telah berhenti mengikuti pengguna ini'], 200);
    }

    public function getUserFollowings($user_id)
    {
        // Temukan pengguna yang followings ingin dilihat
        $user = User::find($user_id);

        if (!$user) {
            return response()->json(['message' => 'Pengguna tidak ditemukan'], 404);
        }

        // Ambil daftar followings pengguna
        $followings = $user->followings;

        return response()->json(['data' => $followings], 200);
    }
}