<?php

use Illuminate\Database\Seeder;

class MonitorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('monitor_types')->insert([
            ['name' => 'MySQL', 'default_host' => '127.0.0.1', 'default_port' => '3306','default_timeout'=>'2','default_times'=>'3'],
            ['name' => 'Http', 'default_host' => '127.0.0.1', 'default_port' => '80','default_timeout'=>'2','default_times'=>'3'],
            ['name' => 'Redis', 'default_host' => '127.0.0.1', 'default_port' => '6379','default_timeout'=>'2','default_times'=>'3'],
            ['name' => 'SSH', 'default_host' => '127.0.0.1', 'default_port' => '22','default_timeout'=>'2','default_times'=>'3'],
            ['name' => 'Custom', 'default_host' => '127.0.0.1', 'default_port' => '0','default_timeout'=>'2','default_times'=>'3'],
        ]);
    }
}
