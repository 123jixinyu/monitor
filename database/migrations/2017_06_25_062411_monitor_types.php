<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MonitorTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //通知类型表
        Schema::create('monitor_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('default_host')->default('127.0.0.1');
            $table->integer('default_port')->default(80);
            $table->integer('default_timeout')->default(2);
            $table->timestamps();
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
