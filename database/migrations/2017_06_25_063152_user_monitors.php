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
            $table->tinyInteger('status')->index()->default(0)->comment('0:正常 1:异常');
            $table->string('remark')->nullable();
            $table->tinyInteger('is_open')->default(0)->index();
            $table->tinyInteger('freq')->default(1)->comment('1:分钟 5：分钟 10：分钟');
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
