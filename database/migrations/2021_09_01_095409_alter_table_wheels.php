<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableWheels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wheels', function (Blueprint $table) {
            $table->after('about', function($table){
                $table->string('PCD')->nullable();
                $table->string('ET')->nullable();
                $table->string('hub')->nullable();
                $table->enum('type', ['alloy_wheel', 'steel_wheel'])->default('alloy_wheel');
            });
        });

        Schema::create('wheel_color_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('wheel_color_id');
            $table->text('image')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes($column = 'deleted_at', $precision = 0);
            $table->engine = 'InnoDB';
            // $table->unique('uuid');
            $table->foreign('wheel_color_id')->references('id')->on('wheel_colors');
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
