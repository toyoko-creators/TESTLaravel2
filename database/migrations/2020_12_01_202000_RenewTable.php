<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('FavoList');
        Schema::dropIfExists('Clothes');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->string('lastname', 50);
            $table->string('firstname', 50);
            $table->string('email', 100);
            $table->primary('email');
            $table->string('VerifyPassword', 256);

        });
        
        Schema::create('Clothes', function (Blueprint $table) {
            $table->string('ImageFile', 256);
            $table->string('email', 100);
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->enum('WearType',['Top', 'Bottom']);
            $table->timestamps();

            $table->unique(['email', 'ImageFile']);
        });
        
        Schema::create('FavoList', function (Blueprint $table) {
            $table->string('email', 100);
            $table->foreign('email')->references('email')->on('users')->onDelete('cascade');
            $table->string('TopFile', 256)->references('ImageFile')->on('Clothes')->onDelete('cascade');
            $table->string('BottomFile', 256)->references('ImageFile')->on('Clothes')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['TopFile','BottomFile']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('FavoList');
        Schema::dropIfExists('Clothes');
        Schema::dropIfExists('follows');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('article_tag');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('users');
    }

}
