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
    <div class="content-wrapper" style="margin-left: 10px" id="monitor-member">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                91 Monitor
                <small>支持Nginx、Redis、MySQL、Sphinx等服务监控。</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12" id="member-list">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">成员列表</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>编号</th>
                                    <th>姓名</th>
                                    <th>头像</th>
                                    <th>邮箱</th>
                                    <th>已创建监控数</th>
                                    <th>注册时间</th>
                                    <th>最后登录时间</th>
                                    <th>登录状态</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($members&&count($members)>0)
                                    @foreach($members as $member)
                                        <tr>
                                            <td>{{$member->id}}</td>
                                            <td>{{$member->name}}</td>
                                            <td>@if($member->avatar)<img src="{{$member->avatar}}" style="width: 40px;height: 40px">@else 无 @endif</td>
                                            <td>{{$member->email}}</td>
                                            <td>{{app(\App\Repository\MonitorRepository::class)->getMonitorCount($member)}}</td>
                                            <td>{{$member->created_at}}</td>
                                            <td>{{$member->last_login_time or '从未登录'}}</td>
                                            <td>{{$member->getLoginStatus()}}</td>
                                            <td><a href="javascript:void(0)" v-on:click="forbidden('{{$member->id}}')">{{$member->getLoginStatusAction()}}</a>
                                                &nbsp;<a href="javascript:void(0)" v-on:click="del({{$member->id}})">删除</a></td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">暂无成员</td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            <div class="pull-right">
                                {!! $members->render() !!}
                            </div>
                            <input type="hidden" v-model="token">
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->


@endsection
@section('js')
    <script>
        var mem=new Vue({
            el:"#member-list",
            data:{
                token:"{{csrf_token()}}"
            },
            methods:{
                forbidden:function(id){
                    _this=this;
                    _this.$http.post('{{route('set_login')}}',{_token:_this.token,id:id}).then(function(res){
                        if(res.data.code=='200'){
                            window.location.reload();
                        }else{
                            dialog.show(res.data.msg);
                        }
                    });
                },
                del:function(id){
                    if(confirm('确定要删除吗？')){
                        _this=this;
                        _this.$http.post('{{route('del_user')}}',{_token:_this.token,id:id}).then(function(res){
                            if(res.data.code=='200'){
                                dialog.show('已删除该用户');
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