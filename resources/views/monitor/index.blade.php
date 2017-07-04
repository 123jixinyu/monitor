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
    </style>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 10px" id="monitor-content">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                FullMonitor
                <small>支持Nginx、Redis、MySQL、Sphinx等服务监控。</small>
            </h1>
            <ol class="list-inline pull-right">
                <li><span class="btn btn-primary" data-toggle="modal" data-target="#monitor-edit">创建监控</span></li>
                <li><span><a href="#">查看帮助</a></span></li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">监控列表</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>监控对象</th>
                                    <th>地址</th>
                                    <th>端口</th>
                                    <th>请求超时设置</th>
                                    <th>触发事件</th>
                                    <th>通知组</th>
                                    <th>备注</th>
                                    <th>状态</th>
                                    <th>是否开启</th>
                                    <th>用户操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($monitors&&count($monitors)>0)
                                    @foreach($monitors as $key=>$monitor)
                                        <tr>
                                            <td>{{$monitor->monitorType->name}}</td>
                                            <td>{{$monitor->host}}</td>
                                            <td>{{$monitor->port}}</td>
                                            <td>{{$monitor->timeout}}秒</td>
                                            <td>{{$monitor->times}}次</td>
                                            <td>{{$monitor->sendGroup->name}}</td>
                                            <td>{{$monitor->remark}}</td>
                                            <td>{{$monitor->getStatus()}}</td>
                                            <td>{{$monitor->getOpen()}}</td>
                                            <td>
                                                <a href='javascript:void(0);' v-on:click="edit({{$monitor->id}})"><span>修改</span></a>
                                                <a href='javascript:void(0);' v-on:click="open({{$monitor->id}})">{{$monitor->getSwitch()}}</a>
                                                <a href='javascript:void(0);' v-on:click.prevent="del({{$monitor->id}})">删除</a>
                                            </td>
                                        </tr>
                                </tbody>
                                @endforeach
                                @else
                                    <tr>
                                        <td colspan="9">暂无监控</td>
                                    </tr>
                                @endif
                                <tfoot>
                                <tr>
                                    <th>Monitor Object</th>
                                    <th>Address</th>
                                    <th>Port</th>
                                    <th>Timeout</th>
                                    <th>Event</th>
                                    <th>Notify</th>
                                    <th>Remark</th>
                                    <th>Status</th>
                                    <th>Open</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="pull-right">
                                {!! $monitors->render() !!}
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

        <div id="monitor-edit" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">保存监控</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form role="form">
                                <div class="box-body">
                                    <!-- Select multiple-->
                                    <div class="form-group">
                                        <label class="required">选择监控对象</label>
                                        <select class="form-control" v-model="type" v-on:change="onChange($event)">
                                            <option v-for="(item,key,index) in items" v-bind:value="item.id">
                                                @{{item.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="required">地址</label>
                                        <input type="text" class="form-control" placeholder=""
                                               v-model="selected.default_host">
                                    </div>
                                    <div class="form-group">
                                        <label class="required">端口</label>
                                        <input type="text" class="form-control" placeholder=""
                                               v-model="selected.default_port">
                                    </div>
                                    <div class="form-group">
                                        <label class="required">请求超时设置（单位秒）</label>
                                        <input type="text" class="form-control" placeholder=""
                                               v-model="selected.default_timeout">
                                    </div>
                                    <div class="form-group">
                                        <label class="required">触发事件（触发报警的次数）</label>
                                        <input type="text" class="form-control" placeholder=""
                                               v-model="selected.default_times">
                                    </div>
                                    <div class="form-group">
                                        <label class="required">通知组</label><span class="pull-right">没有通知组？<a
                                                    href="{{route('monitor_index')}}">去创建</a></span>
                                        <select class="form-control" v-model="selected_group">
                                            <option v-for="(group,key) in groups" v-bind:value="group.id">
                                                @{{group.name}}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>备注</label>
                                        <input type="text" class="form-control" placeholder="请输入备注" v-model="remark">
                                    </div>
                                    <input type="hidden" v-model="token">
                                    <input type="hidden" v-model="id">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" v-model="is_checked"> 是否立即开启
                                        </label>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                        <button type="button" class="btn btn-primary" v-on:click="save()">保存</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- /.content-wrapper -->


@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#monitor-content',
            data: {
                items: [],
                type: '',
                selected: {},
                groups: [],
                selected_group: '',
                remark: '',
                is_checked: 0,
                token: "{{csrf_token()}}",
                id: false
            },
            mounted: function () {
                _this = this;
                _this.$http.get('get_monitor_types').then(function (res) {
                    var data = res.data.data;
                    if (res.data.code == '200' && data) {
                        data.forEach(function (value) {
                            _this.items.push(value);
                        });
                        _this.type = _this.items[0].id;
                        _this.selected = _this.items[0];
                    }
                });
                _this.$http.get('get_groups').then(function (res) {
                    var data = res.data.data;
                    if (res.data.code == '200' && data) {
                        data.forEach(function (value) {
                            _this.groups.push(value);
                        });
                        _this.selected_group = _this.groups[0].id;
                    }
                });
            },
            methods: {
                onChange: function ($event) {
                    _this = this;
                    _this.selected = _this.items[$event.target.selectedIndex];
                },
                save: function () {
                    _this = this;
                    var form_data = {
                        _token: _this.token,
                        monitor_id: _this.type,
                        group_id: _this.selected_group,
                        host: _this.selected.default_host,
                        port: _this.selected.default_port,
                        timeout: _this.selected.default_timeout,
                        times: _this.selected.default_times,
                        remark: _this.remark,
                        is_open: _this.is_checked ? 1 : 0
                    };
                    if (_this.id) {
                        form_data.id = _this.id;
                    }
                    _this.$http.post('save_user_monitor', form_data).then(function (res) {
                        if (res.data.code == '200') {
                            $('#monitor-edit').modal('hide');
                            window.location.reload();
                        } else {
                            dialog.show(res.data.msg);
                        }
                    });
                },
                edit: function (id) {
                    _this = this;
                    _this.$http.get("get_user_monitor_detail", {
                        params: {
                            id: id
                        }
                    }).then(function (res) {
                        if (res.data.code == '200') {
                            var detail = res.data.data;
                            if (detail) {
                                _this.type = detail.monitor_id;
                                _this.selected_group = detail.group_id;
                                _this.selected.default_host = detail.host;
                                _this.selected.default_port = detail.port;
                                _this.selected.default_timeout = detail.timeout;
                                _this.remark = detail.remark;
                                _this.is_checked = detail ? true : false;
                                _this.id = detail.id;
                            }
                            $("#monitor-edit").modal();
                        }
                    });
                },
                del: function (id) {
                    if(confirm('确定删除?')){
                        _this = this;
                        _this.$http.post('del_user_monitor', {id: id, _token: _this.token}).then(function (res) {
                            if (res.data.code == '200') {
                                window.location.reload();
                            }
                        });
                    }
                },
                open:function(id){
                    _this = this;
                    _this.$http.post('open_handle', {id: id, _token: _this.token}).then(function (res) {
                        if (res.data.code == '200') {
                            window.location.reload();
                        }
                    });
                }
            }
        });

    </script>
@endsection