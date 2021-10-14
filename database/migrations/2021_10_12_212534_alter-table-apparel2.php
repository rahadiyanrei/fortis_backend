<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableApparel2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apparels', function (Blueprint $table) {
            $table->after('sizes', function($table){
                $table->string('tokopedia_url')->nullable();
                $table->string('shopee_url')->nullable();
                $table->string('lazada_url')->nullable();
                $table->string('bukalapak_url')->nullable();
                $table->string('blibli_url')->nullable();
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
