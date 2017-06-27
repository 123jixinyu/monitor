<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserMonitors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //用户和监控类型的关系表
        Schema::create('user_monitors',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('monitor_id')->index();
            $table->integer('group_id')->index();
            $table->string('host')->index();
            $table->integer('port')->index();
            $table->integer('timeout')->index();
            $table->integer('times')->index();
            $table->string('remark')->nullable();
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
