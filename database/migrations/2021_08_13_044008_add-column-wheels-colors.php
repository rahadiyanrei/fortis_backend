<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnWheelsColors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wheel_colors', function (Blueprint $table) {
            $table->after('wheel_id', function($table){
                $table->string('color_name');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wheel_colors', function (Blueprint $table) {
            $table->dropColumn('color_name');
        });
    }
}
