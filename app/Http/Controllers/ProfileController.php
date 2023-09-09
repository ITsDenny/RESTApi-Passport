<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Profil pengguna tidak ditemukan'], 404);
        }
        $followerCount = Follow::where('following_id', $id)->count();
        $followingCount = Follow::where('followers_id', $id)->count();

        
        $profileData = [
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'username' => $user->username,
            'DateOfBirth' => $user->DateOfBirth,
            'image' => $user->image,
            'phone_number' => $user->phone_number,
            'follower_count' => $followerCount,
            'following_count' => $followingCount
        ];

        return response()->json(['data' => $profileData], 200);
    }

    public function updateProfileImage(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi 
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            $image = $request->file('image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'public/image/profile/' . $imageName;
            Storage::put($imagePath, file_get_contents($image));

            $user->image = $imageName;
            $user->save();

            return response()->json(['message' => 'Gambar profil berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate gambar profil' . $e], 500);
        }
    }

    public function searchUser(Request $request)
    {
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');


        $user = User::where('firstName', $firstName)->where('lastName', $lastName)->get();

        return response()->json([
            'data' => $user
        ], 200);
    }
}
