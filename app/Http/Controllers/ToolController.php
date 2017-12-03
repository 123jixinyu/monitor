<?php

namespace App\Http\Controllers;

use App\Classes\Tools\Ping;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\SeekException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Predis\ClientException;
use Validator;

class ToolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tool.index');
    }

    /**
     * 显示工具页面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function tool(Request $request)
    {
        $type = $request->route('type');
        $path = 'tool.' . $type;
        if (!view()->exists($path)) {
            return view('errors.404');
        }
        return view($path);
    }

    /**
     * 检测网络是否正常
     * @param Request $request
     * @param Ping $ping
     * @return string
     */
    public function ping(Request $request, Ping $ping)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'ip' => 'required'
        ], [
            'ip.required' => '必须输入指定ip'
        ]);
        if (count($validator->errors())) {
            return api_response('400', $validator->errors()->first());
        }
        $ip = array_get($params, 'ip');
        $ip=trim($ip);
        $response = $ping->check($ip);
        if(!$response){
            return api_response('300','查询到网络异常');
        }
        $command=sprintf("ping %s -c 3",$ip);
        $output=shell_exec($command);
        return api_response('200','success',$output);

    }

    /**
     * 查看路由转发
     * @param Request $request
     * @param Ping $ping
     * @return string
     */
    public function trace(Request $request, Ping $ping)
    {
        $params = $request->all();
        $validator = Validator::make($params, [
            'ip' => 'required'
        ], [
            'ip.required' => '必须输入指定ip'
        ]);
        if (count($validator->errors())) {
            return api_response('400', $validator->errors()->first());
        }
        $ip = array_get($params, 'ip');
        $ip=trim($ip);
        $response = $ping->check($ip);
        if(!$response){
            return api_response('300','查询到网络异常');
        }
        $command=sprintf("traceroute %s -m 5",$ip);
        $output=shell_exec($command);
        return api_response('200','success',$output);

    }

    /**
     * 检查地址响应码
     * @param Request $request
     * @return string
     */
    public function http(Request $request){
        $params = $request->all();
        $validator = Validator::make($params, [
            'ip' => 'required'
        ], [
            'ip.required' => '必须输入指定ip'
        ]);
        if (count($validator->errors())) {
            return api_response('400', $validator->errors()->first());
        }
        $ip = array_get($params, 'ip');
        $ip=trim($ip);
        $client=new Client([['connect_timeout' => 5, 'timeout' => 5]]);
        $statusCode='其他';
        try{
            $response=$client->request("GET",$ip);
            $statusCode=$response->getStatusCode();
            $message=config('httpcode'.'.'.$statusCode,'');
        }
        catch (\GuzzleHttp\Exception\ClientException $ex){
            $statusCode=$ex->getCode();
            $message=config('httpcode'.'.'.$statusCode,'');
        }
        catch (ServerException $ex){
            $statusCode=$ex->getCode();
            $message=config('httpcode'.'.'.$statusCode,'');
        }
        catch (TooManyRedirectsException $ex){
            $statusCode=$ex->getCode();
            $message=$ex->getMessage();
        }
        catch (ConnectException $ex){
            $message='无法解析地址';
        }
        catch (RequestException $ex){
            $statusCode=$ex->getCode();
            $message=$ex->getMessage();
        }
        catch (SeekException $ex){
            $statusCode=$ex->getCode();
            $message=$ex->getMessage();
        }
        catch (TransferException $ex){
            $statusCode=$ex->getCode();
            $message=$ex->getMessage();
        }
        catch ( \Exception $ex){
            $message=$ex->getMessage();
        }
        return api_response('200','success',[
            'status_code'=>$statusCode,
            'status_message'=>$message
        ]);
    }
}
