<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Servers extends Migration {

    /**
     * Run the migrations.
     * @return void
     */
    public function up() {

        Schema::create('servers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('name')->comment('服务器名');
            $table->string('key')->comment('服务器key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down() {
        //
        Schema::drop("servers");
    }
}
