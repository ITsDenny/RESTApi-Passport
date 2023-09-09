<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLikeUnlikeToPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('like_count')->default(0);
            $table->unsignedInteger('unlike_count')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('like_count');
            $table->dropColumn('unlike_count');
        });
    }
}
