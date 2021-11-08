<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableFieldDeletedAt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wheels', function (Blueprint $table) {
            $table->after('updated_at', function($table){
                $table->softDeletes($column = 'deleted_at', $precision = 0);
            });
        });

        Schema::table('dealers', function (Blueprint $table) {
            $table->after('updated_at', function($table){
                $table->softDeletes($column = 'deleted_at', $precision = 0);
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
        //
    }
}
