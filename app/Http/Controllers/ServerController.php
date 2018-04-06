<?php

namespace App\Http\Controllers;

use App\Entities\Server;
use App\Repository\SystemRepository;
use Auth;
use Illuminate\Cache\RedisStore;
use Illuminate\Http\Request;
use Validator;
use Redis;
use Crypt;

class ServerController extends Controller {

    /**
     * 服务器管理首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(SystemRepository $systemRepository) {

        $user    = Auth::user();
        $servers = $user->servers;
        foreach ($servers as $server) {
            $key = $server->key;
            //获取系统信息
            $sysInfo                     = $systemRepository->getSystemInfo();
            $sysInfo['disk_total_space'] = round(@disk_total_space(".") / (1024 * 1024 * 1024), 1);
            $sysInfo['disk_free_space']  = round(@disk_free_space(".") / (1024 * 1024 * 1024), 1);
            $sysInfo['memTotal']         = round($sysInfo['memTotal'] / 1024, 3);
            $sysInfo['memFree']          = round($sysInfo['memFree'] / 1024, 3);
            $sysInfo['memCached']        = round($sysInfo['memCached'] / 1024, 3);
            $sysInfo['memRealUsed']      = round($sysInfo['memRealUsed'] / 1024, 3);
            $sysInfo['memRealFree']      = round($sysInfo['memRealFree'] / 1024, 3);
            $sysInfo['now']              = date('Y-m-d H:i:s');
            $sysInfo['net']              = $systemRepository->getNetworkFlow();
            $format                      = $systemRepository->formatSystemInfo($sysInfo);
            $server->sysInfo             = $format;
        }
        return view('server.index', [
            'servers' => $servers
        ]);
    }

    public function save(Request $request) {

        $params    = $request->all();
        $validator = Validator::make($params, [
            'id'   => 'exists:servers,id',
            'name' => 'required|string|max:255',
            'key'  => 'required|string|max:255'
        ], [
            'name.required' => '服务器名称必须填写',
            'name.string'   => '服务器名称长度不合法(1～255)',
            'name.max'      => '服务器名称长度不合法(1～255)',
            'key.required'  => '服务器key必须填写',
            'key.string'    => '服务器key长度不合法(1～255)',
            'key.max'       => '服务器key长度不合法(1～255)',
        ]);

        if ($validator->fails()) {
            return api_response('400', $validator->errors()->first());
        }
        $id   = array_get($params, 'id');
        $name = array_get($params, 'name');
        $key  = array_get($params, 'key');
        $user = Auth::user();

        try{
            $keyArray=json_decode(base64_decode($key),true);
        }catch (\Exception $ex){
            return api_response('400', '非法的key');
        }
        if(!is_array($keyArray)||$keyArray['user_id']!=$user->id){
            return api_response('400', '非法的key');
        }

        if ($id) {
            $server = Server::where('id', $id)->where('user_id', $user->id)->first();
        } else {
            $server = new Server();
        }

        $server->name    = $name;
        $server->key     = $key;
        $server->user_id = $user->id;
        $server->save();

        return api_response(200, '保存成功');
    }

    public function delete(Request $request) {

        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => 'required|exists:servers,id'
        ]);
        if ($validator->fails()) {
            return api_response(300, '非法的请求');
        }
        $id   = array_get($params, 'id');
        $user = Auth::user();

        Server::where('user_id', $user->id)->where('id', $id)->delete();

        return api_response(200, '删除成功');
    }

    public function detail(Request $request) {
        $params = $request->all();

        $validator = Validator::make($params, [
            'id' => 'required|exists:servers,id'
        ]);
        if ($validator->fails()) {
            return api_response(300, '非法的请求');
        }
        $id   = array_get($params, 'id');
        $user = Auth::user();

        return api_response(200, '成功', Server::where('user_id', $user->id)->where('id', $id)->first());
    }

    public function getAllSysInfo(SystemRepository $systemRepository) {

        $user    = Auth::user();
        $servers = $user->servers;
        foreach ($servers as $server) {
            $key = $server->key;
            //获取系统信息
            $redis = new Redis();
            $redis->connect(env('REDIS_HOST'), env('REDIS_POST'));
            $redis->auth(env('REDIS_PASSWORD'));
            $length = $redis->lLen($key);
            if ($length >= 1) {
                if ($length > 1) {
                    $value = $redis->rPop($key);
                } else {
                    $value = $redis->lRange($key, 0, 1)[0];
                }
                $sysInfo         = json_decode($value, true);
                $format          = $systemRepository->formatSystemInfo($sysInfo);
                $server->sysInfo = $format;
            }

        }
        return api_response(200, '成功', ['servers' => $servers]);
    }

    public function generateKey(Request $request) {

        $user = Auth::user();
        return api_response(200, '成功', [
            'key' => base64_encode(json_encode([
                    'user_id'   => $user->id,
                    'timestamp' => time()
                ]
            ))
        ]);
    }

}