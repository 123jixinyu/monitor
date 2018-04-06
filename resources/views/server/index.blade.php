@extends('adminlte::page')
@section('css')
    <style>
        tr > td, tr > th {
            text-align: center;
        }

        .required:after {
            content: "*";
            color: red;
        }

        .all-system {
            margin-top: 50px;
        }

        .box.box-info {
            border-top-color: #c6e0e6;
        }

        input.knob {
            border: none;
        }
    </style>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 10px" id="get_real_time_system">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                91 Monitor
                <small>支持Nginx、Redis、MySQL、Sphinx等服务监控。</small>
            </h1>
            <ol class="list-inline pull-right">
                <li><span class="btn btn-primary" data-toggle="modal" data-target="#server-add">添加服务器</span></li>
                <li><span><a href="#">查看帮助</a></span></li>
            </ol>
        </section>

        <section class="content all-system">
            <div class="row">

                <div class="col-md-6" v-for="server in servers">
                    <!-- Left col -->
                    <div class="col-md-12">
                        <!-- TABLE: LATEST ORDERS -->
                        <div class="box box-info">
                            <div class="box-header with-border">
                                <h3 class="box-title">@{{server.name}}</h3>

                                <div class="box-tools pull-right">
                                    <button type="button" class="btn btn-box-tool" @click="edit(server.id)">修改</button>
                                    <button type="button" class="btn btn-box-tool" @click="del(server.id)">删除</button>
                                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                                class="fa fa-minus">&nbsp;伸缩</i>
                                    </button>
                                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                                class="fa fa-times">&nbsp;关闭窗口</i></button>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <table class="table table-hover table-bordered" v-if="server.sysInfo!==undefined">
                                    <tr>
                                        <td>内核信息</td>
                                        <td colspan="3">@{{server.sysInfo.server_tag}}</td>
                                    </tr>
                                    <tr>
                                        <td>CPU型号 @{{server.sysInfo.num}}核</td>
                                        <td colspan="3">@{{server.sysInfo.model}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>系统负载</td>
                                        <td>@{{server.sysInfo.loadAvg}}</td>
                                        <td>系统时间:@{{server.sysInfo.now}}</td>
                                        <td>已运行时间:@{{server.sysInfo.uptime}}</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div>存储空间</div>
                                            <input type="text" class="knob" value="0" data-width="90" data-height="90"
                                                   data-fgColor="#F56954">
                                            <div class="knob-label">
                                                剩余空间/总空间：@{{server.sysInfo.disk_free_space}}/@{{server.sysInfo.disk_total_space}}
                                            </div>
                                        </td>
                                        <td>
                                            <div>内存状况</div>

                                            <input type="text" class="knob" value="0" data-width="90" data-height="90"
                                                   data-fgColor="#932AB6">
                                            <div class="knob-label">
                                                剩余/总内存：@{{server.sysInfo.memFree}}/@{{server.sysInfo.memTotal}}
                                            </div>

                                        </td>
                                        <td>
                                            <div>Cache内存</div>

                                            <input type="text" class="knob" value="0" data-width="90" data-height="90"
                                                   data-fgColor="#F56954">
                                            <div class="knob-label">
                                                使用率/总cache化内存：@{{server.sysInfo.memCachedPercent}}/@{{server.sysInfo.memCached}}
                                            </div>
                                        </td>
                                        <td>
                                            <div>真实内存</div>

                                            <input type="text" class="knob" value="0" data-width="90" data-height="90"
                                                   data-fgColor="#00C0EF">
                                            <div class="knob-label">
                                                空闲/已使用：@{{server.sysInfo.memRealFree}}/@{{server.sysInfo.memRealUsed}}
                                            </div>
                                        </td>
                                    </tr>

                                </table>

                                <table class="table table-hover table-bordered" v-if="server.sysInfo==undefined">
                                    <tr>
                                        <td colspan="4">该服务器尚未配置或出现异常</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.box -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
        </section>

        <div id="server-add" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">服务器</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form role="form">
                                <div class="box-body">
                                    <!-- Select multiple-->
                                    <div class="form-group">
                                        <label class="required">服务器名称</label>
                                        <input type="text" class="form-control" placeholder="" v-model="server_name">

                                    </div>
                                    <div class="form-group">
                                        <label class="required">服务器key</label>
                                        <a href="javascript:void(0)" @click="gen" v-if="server_key==''">点击生成</a>
                                        <a href="javascript:void(0)" @click="gen" v-if="server_key">重新生成</a>
                                        <input type="text" class="form-control" placeholder="" v-model="server_key"
                                               disabled="disabled">
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" @click="save">保存</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    </div>

@endsection
@section('js')
    <script src="{{asset('assets/js/jquery.knob.js')}}"></script>


    <script>
      sys = new Vue({
        el     : '#get_real_time_system',
        data   : {
          servers    : [],
          token      : "{{csrf_token()}}",
          server_name: '',
          server_key : '',
          server_id  : ''
        },
        mounted: function(){
          _this = this;
          _this.getInfo();
          if(_this && !_this._isDestroyed){
            setInterval(function(){
              _this.getInfo();
              $(".knob").knob({
                readOnly: true
              });
            }, 3000);
          }
        },
        methods: {
          getInfo: function(){
            _this = this;
            _this.$http.post('{{route("server_get_all_info")}}', {_token: _this.token}).then(function(res){
              if(res.data.code === 200){
                var list = res.data.data.servers;
                _this.servers = [];
                list.forEach(function(value){
                  _this.servers.push(value);
                });
                for(var i = 1; i <= _this.servers.length; i++){
                  if(_this.servers[i - 1].sysInfo != undefined){
                    $(".knob").eq((i - 1) * 4).val(_this.servers[i - 1].sysInfo.disk_percent);
                    $(".knob").eq(1 + (i - 1) * 4).val(_this.servers[i - 1].sysInfo.mem_percent);
                    $(".knob").eq(2 + (i - 1) * 4).val(_this.servers[i - 1].sysInfo.memCachedPercent);
                    $(".knob").eq(3 + (i - 1) * 4).val(_this.servers[i - 1].sysInfo.memRealPercent);
                    $(".knob").trigger('change');
                  }
                }
              }
            });
          },
          save   : function(){
            _this = this;
            var form = {
              _token: _this.token,
              name  : _this.server_name,
              key   : _this.server_key
            };
            if(_this.server_id){
              form.id = _this.server_id;
            }
            _this.$http.post('{{route('server_save')}}', form).then(function(res){
              if(res.data.code == '200'){
                $('#monitor-edit').modal('hide');
                window.location.reload();
              }
              dialog.show(res.data.msg);
            });
          },
          del    : function(id){

            if(confirm('你确定要删除么？')){
              _this = this;
              _this.$http.post("{{route('server_delete')}}", {
                id    : id,
                _token: _this.token

              }).then(function(res){
                if(res.data.code == '200'){
                  window.location.reload();
                }
                dialog.show(res.data.msg);

              });
            }
          },
          edit   : function(id){
            _this = this;
            _this.$http.get("{{route('server_detail')}}", {
              params: {
                id: id
              }
            }).then(function(res){
              if(res.data.code == '200'){
                var detail = res.data.data;
                if(detail){
                  _this.server_id = detail.id;
                  _this.server_name = detail.name;
                  _this.server_key = detail.key;
                }
                $("#server-add").modal();
              }
            });
          },
          gen    : function(){
            _this = this;
            _this.$http.post("{{route('server_generate')}}", {
              _token: _this.token

            }).then(function(res){
              if(res.data.code == '200'){
                _this.server_key = res.data.data.key;
              }else{
                dialog.show(res.data.msg);
              }
            });
          }
        }
      });
    </script>
@endsection