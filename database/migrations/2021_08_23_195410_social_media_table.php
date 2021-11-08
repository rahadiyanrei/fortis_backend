<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_medias', function (Blueprint $table) {
            $table->increments('id');
            $table->text('facebook')->nullable();
            $table->text('linkedin')->nullable();
            $table->text('youtube')->nullable();
            $table->text('twitter')->nullable();
            $table->text('instagram')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('social_medias');
    }
}
