<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WheelColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheel_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wheel_id');
            $table->text('image')->nullable();
            $table->string('color_hex')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->engine = 'InnoDB';
            // $table->unique('uuid');
            $table->foreign('wheel_id')->references('id')->on('wheels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('wheel_colors');
    }
}
