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
                <li><span class="btn btn-primary" data-toggle="modal" data-target="#monitor-group">创建通知组</span></li>
                <li><span><a href="#">查看帮助</a></span></li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    @if($user_groups&&count($user_groups)>0)
                        @foreach($user_groups as $group)
                            <div class="box">
                                <div class="box-header">
                                    <h3 class="box-title text-info">{{$group->name}}</h3>
                                    <div class="box-tools">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <div class="input-group-btn">
                                                <span class="btn btn-info">添加成员</span>
                                                <span class="btn btn-default" v-on:click="edit_group({{$group->id}})">修改组名</span>
                                                <span class="btn btn-default" v-on:click="del_group({{$group->id}})">删除该分组</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-header -->
                                <div class="box-body table-responsive no-padding">
                                    <table class="table table-hover">
                                        <tr>
                                            <th>姓名</th>
                                            <th>通知类型</th>
                                            <th>手机号码</th>
                                            <th>邮箱</th>
                                            <th>备注/说明</th>
                                            <th>添加时间</th>
                                            <th>操作</th>
                                        </tr>
                                        @if($group->senderGroup&&count($group->senderGroup)>0)
                                            @foreach($group->senderGroup as $people)
                                                <tr>
                                                    <td>{{$people->name}}</td>
                                                    <td>{{$people->type}}</td>
                                                    <td>{{$people->phone}}</td>
                                                    <td>{{$people->email}}</td>
                                                    <td>{{$people->remark}}</td>
                                                    <td>{{$people->created_at}}</td>
                                                    <td>
                                                        <a href="javascript:void(0)">修改</a>
                                                        <a href="javascript:void(0)">删除</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">暂无成员</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                                <!-- /.box-body -->
                            </div>
                        @endforeach
                    @else
                        <p class="btn text-primary" data-toggle="modal" data-target="#monitor-group">点击创建通知组</p>
                    @endif
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <div id="monitor-group" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">保存分组</h4>
                    </div>
                    <div class="modal-body">
                        <div class="box box-primary">
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form role="form">
                                <div class="box-body">
                                    <!-- Select multiple-->
                                    <div class="form-group">
                                        <label class="required">通知组名称</label>
                                        <input type="text" class="form-control" placeholder="" v-model="group">
                                    </div>
                                    <input type="hidden" v-model="group_id">
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
        var grp = new Vue({
            el: '#monitor-content',
            data: {
                group: '',
                group_id:false,
                token: '{{csrf_token()}}'
            },
            methods: {
                save: function () {
                    _this = this;
                    var form_data = {_token: _this.token, group: _this.group};
                    if(_this.group_id){
                        form_data.id=_this.group_id;
                    }
                    _this.$http.post('save_group', form_data).then(function (res) {
                        if (res.data.code == '200') {
                            window.location.reload();
                        } else {
                            dialog.show(res.data.msg);
                        }
                    });
                },
                edit_group:function(id){
                    _this=this;
                    _this.$http.get('get_group_detail',{params:{id:id}}).then(function(res){
                        if(res.data.code=='200'){
                            var obj=res.data.data;
                            _this.group=obj.name;
                            _this.group_id=obj.id;
                            $("#monitor-group").modal();
                        }else{
                            dialog.show(res.data.msg);
                        }
                    });
                },
                del_group:function(id){
                    if(confirm('确定删除？删除后将无法还原。')){
                        _this=this;
                        _this.$http.post('del_group',{id:id,_token:_this.token}).then(function(res){
                            if(res.data.code=='200'){
                                window.location.reload();
                            }else{
                                dialog.show(res.data.msg);
                            }
                        });
                    }
                }
            }
        });
    </script>
@endsection