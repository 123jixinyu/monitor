@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
    <style>
        .box-header {
            background-color : #7674A7;
        }
    </style>
@endsection
@section('content')
    <div id="real-time-info">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">服务器基本信息</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td>服务器域名/IP地址</td>
                            <td colspan="3">{{$_SERVER['SERVER_NAME']}}
                                ( {{('/'==DIRECTORY_SEPARATOR)?$_SERVER['SERVER_ADDR']:@gethostbyname($_SERVER['SERVER_NAME'])}}
                                )
                            </td>
                        </tr>
                        <tr>
                            <td>服务器标识</td>
                            <td colspan="3">{{$sysInfo['win_n']!=''?$sysInfo['win_n']:@php_uname()}}</td>
                        </tr>
                        <tr>
                            <td>服务器操作系统</td>
                            <td>{{$os[0]}} &nbsp;内核版本：{{'/'==DIRECTORY_SEPARATOR?$os[2]:$os[1]}}</td>
                            <td>服务器解译引擎</td>
                            <td>{{$_SERVER['SERVER_SOFTWARE']}}</td>
                        </tr>
                        <tr>
                            <td>服务器语言</td>
                            <td>{{getenv("HTTP_ACCEPT_LANGUAGE")}}</td>
                            <td>服务器端口</td>
                            <td>{{$_SERVER['SERVER_PORT']}}</td>
                        </tr>
                        <tr>
                            <td>服务器主机名</td>
                            <td>{{'/'==DIRECTORY_SEPARATOR?$os[1]:$os[2]}}</td>
                            <td>绝对路径</td>
                            <td>{{ $_SERVER['DOCUMENT_ROOT']?str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']):str_replace('\\','/',dirname(__FILE__))}}</td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div class="row ">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">服务器实时信息</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered">
                        <tr>
                            <td>服务器当前时间</td>
                            <td>@{{sysInfo.now}}</td>
                            <td>服务器已运行时间</td>
                            <td>@{{sysInfo.uptime}}</td>
                        </tr>
                        <tr>
                            <td>CPU型号@{{num}}核</td>
                            <td colspan="3">@{{model}}</td>
                        </tr>
                        <tr>
                            <td>系统平均负载</td>
                            <td colspan="3">@{{sysInfo.loadAvg}}</td>
                        </tr>
                    </table>
                    <div class="text-center col-md-3">
                        <div>剩余空间</div>
                        <input type="text" class="knob" value="30" data-width="90" data-height="90"
                               data-fgColor="#3c8dbc">
                        <div class="knob-label">总空间@{{sysInfo.disk_total_space}}GB，剩余@{{sysInfo.disk_free_space}}
                            GB
                        </div>

                    </div>
                    <div class="text-center col-md-3">
                        <div>剩余内存</div>
                        <input type="text" class="knob" value="30" data-width="90" data-height="90"
                               data-fgColor="#932AB6">
                        <div class="knob-label">物理内存@{{sysInfo.memTotal}} GB，已使用@{{sysInfo.memFree}}GB</div>
                    </div>
                    <div class="text-center col-md-3">
                        <div>Cache已使用</div>
                        <input type="text" class="knob" value="30" data-width="90" data-height="90"
                               data-fgColor="#F56954">
                        <div class="knob-label">Cache化内存@{{sysInfo.memCached}}GB，使用率@{{sysInfo.memCachedPercent}}
                            %
                        </div>
                    </div>
                    <div class="text-center col-md-3">
                        <div>真实内存已使用</div>
                        <input type="text" class="knob test-knob" value="30" data-width="90" data-height="90"
                               data-fgColor="#00C0EF">
                        <div class="knob-label">真实内存使用@{{sysInfo.memRealUsed}} GB,真实内存空闲 @{{sysInfo.memRealFree}}
                            GB
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">网络使用情况</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover table-bordered">
                        <tr v-for="item in net">
                            <td>@{{item.net}}</td>
                            <td>入网:<span>@{{item.input}}</span>G</td>
                            <td>出网:<span>@{{item.out}}</span>G</td>
                        </tr>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('assets/js/jquery.knob.js')}}"></script>
    <script>
        sys = new Vue({
            el: '#real-time-info',
            data: {
                sysInfo: {},
                num: 0,
                model: '',
                net: [
                    {net:"xxx",input:"212",output:'55'}
                ]
            },
            mounted: function () {
                _this = this;
                _this.getInfo();
                if (_this && !_this._isDestroyed) {
                    setInterval(function () {
                        _this.getInfo();
                    }, 3000);
                }
            },
            methods: {
                getInfo: function () {
                    _this = this;
                    _this.$http.get('{{route("get_real_time_info")}}').then(function (res) {
                        if (res.data.code == '200') {
                            _this.net=[];
                            _this.sysInfo = res.data.data.sysInfo;
                            _this.num = _this.sysInfo.cpu.num;
                            _this.model = _this.sysInfo.cpu.model;
                            $(".knob").knob({
                                readOnly: true
                            });
                            $(".knob").eq(0).val(_this.free_space_percent);
                            $(".knob").eq(1).val(_this.sysInfo.memPercent);
                            $(".knob").eq(2).val(_this.sysInfo.memCachedPercent.toFixed(2));
                            $(".knob").eq(3).val(_this.sysInfo.memRealPercent);
                            $(".knob").trigger('change');
                            var list=res.data.data.net;
                            if (list.length > 0) {
                                list.forEach(function(item){
                                    _this.net.push(item);
                                });
                            }
                        }
                    });
                }
            },
            computed: {
                free_space_percent: function () {
                    _this = this;
                    return (_this.sysInfo.disk_free_space / _this.sysInfo.disk_total_space * 100).toFixed(2);
                },
                real_space_percent: function () {
                    _this = this;
                    return (_this.sysInfo.memRealFree / _this.sysInfo.memRealUsed * 100).toFixed(2);
                }
            }
        });
    </script>
@endsection