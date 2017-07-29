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
                91 Monitor
                <small>支持Nginx、Redis、MySQL、Sphinx等服务监控。</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
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
                                    <th>注册时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if($members&&count($members)>0)
                                    @foreach($members as $member)
                                        <tr>
                                            <td>{{$member->id}}</td>
                                            <td>{{$member->name}}</td>
                                            <td>@if($member->avatar)<img src="{{$member->avatar}}@else 无 @endif"></td>
                                            <td>{{$member->email}}</td>
                                            <td>{{$member->created_at}}</td>
                                            <td><a href="javascript:void(0)">禁止登录</a>&nbsp;<a href="javascript:void(0)">删除</a></td>
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


    </script>
@endsection