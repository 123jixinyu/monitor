<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SenderPeople extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //通知人群表
        Schema::create('sender_peoples',function(Blueprint $table){
            $table->increments('id');
            $table->integer('group_id')->index();
            $table->string('name');
            $table->tinyInteger('type')->default(1)->comment('1:邮件通知 2:短信通知 3:邮件+短信');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
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
