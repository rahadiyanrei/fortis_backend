<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DealerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->engine = 'InnoDB';
        });

        Schema::create('dealers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->decimal('long', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->unsignedInteger('province_id');
            $table->string('name');
            $table->string('address');
            $table->string('email');
            $table->string('phone_number');
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->engine = 'InnoDB';
            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('provinces');
        Schema::drop('dealers');
    }
}
