<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MonitorLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //通知人群表
        Schema::create('monitor_logs',function(Blueprint $table){
            $table->increments('id');
            $table->integer('user_monitor_id')->index();
            $table->integer('monitor_id')->index();
            $table->string('monitor_type')->index();
            $table->string('host');
            $table->string('port');
            $table->string('group_name');
            $table->integer('user_id')->index();
            $table->tinyInteger('status')->index()->default(0)->comment('0:正常1:异常');
            $table->tinyInteger('is_send')->index()->default(0)->comment('0:不发送通知1:发送通知');
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
