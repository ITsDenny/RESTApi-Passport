<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
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

        $profileData = [
            'firstName' => $user->firstName,
            'lastName' => $user->lastName,
            'username' => $user->username,
            'DateOfBirth' => $user->DateOfBirth,
            'image' => $user->image,
        ];

        return response()->json(['data' => $profileData], 200);
    }
    public function updateProfileImage(Request $request)
    {
        try {
            $user = Auth::user();

            // Validasi permintaan untuk memastikan itu adalah file gambar yang valid
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Sesuaikan dengan kebutuhan Anda
            ]);

            // Mengambil file gambar yang diunggah
            $image = $request->file('image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'public/image/profile/' . $imageName;
            Storage::put($imagePath, file_get_contents($image));

            // Update URL gambar profil dalam tabel user
            $user->image = $imageName;
            $user->save();

            // Kembalikan respons sukses
            return response()->json(['message' => 'Gambar profil berhasil diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat mengupdate gambar profil' . $e], 500);
        }
    }
}
