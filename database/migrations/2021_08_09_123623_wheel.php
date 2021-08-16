<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Wheel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wheels', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('name');
            $table->text('image')->nullable();
            $table->tinyInteger('is_new_release')->default(0);
            $table->tinyInteger('is_discontinued')->default(0);
            $table->enum('brand', ['pako', 'inko', 'fortis', 'avantech'])->default('pako');
            $table->longText('about');
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->engine = 'InnoDB';
            // $table->unique('uuid');
        });

        Schema::create('wheel_sizes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wheel_id');
            $table->enum('diameter', [
                10,
                12,
                13,
                14,
                15,
                16,
                16.5,
                17,
                18,
                19,
                19.5,
                20,
                21,
                22,
                23,
                24
            ])->default(10);
            $table->mediumText('option_width')->nullable();
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
        Schema::drop('wheels');
        Schema::drop('wheel_sizes');
    }
}
