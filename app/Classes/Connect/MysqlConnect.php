<?php
namespace App\Classes\Connect;

use DB;

class MysqlConnect
{
    /**
     * 尝试连接mysql
     * @return bool|string
     */
    public function tryConnect()
    {
        try {
            DB::connection();
            return true;
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }
}