<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class GalleryVehicleBrand extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('logo')->nullable();
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->text('image_thumbnail')->nullable();
            $table->string('title');
            $table->unsignedInteger('vehicle_brand_id')->nullable();
            $table->unsignedInteger('wheel_id')->nullable();
            $table->enum('type', ['car', 'wheel'])->default('car');
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->engine = 'InnoDB';
            $table->foreign('vehicle_brand_id')->references('id')->on('vehicle_brands');
            $table->foreign('wheel_id')->references('id')->on('wheels');
        });

        Schema::create('gallery_images', function (Blueprint $table) {
            $table->increments('id');
            $table->text('image')->nullable();
            $table->unsignedInteger('gallery_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->engine = 'InnoDB';
            $table->foreign('gallery_id')->references('id')->on('galleries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}