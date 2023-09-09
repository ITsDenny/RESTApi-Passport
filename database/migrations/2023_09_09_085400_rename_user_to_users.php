<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameUserToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('user', 'users'); // Mengubah nama tabel dari 'user' menjadi 'users'
    }

    public function down()
    {
        Schema::rename('users', 'user'); // Jika perlu mengembalikan nama tabel ke 'user'
    }
}
