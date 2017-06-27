@extends('adminlte::page')
@section('css')
    <style>
        tr>td,tr>th{
            text-align: center;
        }
    </style>
@endsection
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper" style="margin-left: 10px">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                FullMonitor
                <small>支持Nginx、Redis、MySQL、Sphinx等服务监控。</small>
            </h1>
            <ol class="list-inline pull-right">
                <li><span class="btn btn-primary">创建监控</span></li>
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
                                    <th>用户操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>MySQL</td>
                                    <td>127.0.0.1</td>
                                    <td>3306</td>
                                    <td>2s</td>
                                    <td>3次</td>
                                    <td>公司</td>
                                    <td>
                                        <span class="btn btn-primary">编辑</span>
                                        <span class="btn btn-primary">关闭/开启</span>
                                        <span class="btn btn-primary">查看备注</span>
                                        <span class="btn btn-primary">删除</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MySQL</td>
                                    <td>127.0.0.1</td>
                                    <td>3306</td>
                                    <td>2s</td>
                                    <td>3次</td>
                                    <td>公司</td>
                                    <td>
                                        <span class="btn btn-primary">编辑</span>
                                        <span class="btn btn-primary">关闭/开启</span>
                                        <span class="btn btn-primary">查看备注</span>
                                        <span class="btn btn-primary">删除</span>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Monitor Object</th>
                                    <th>Address</th>
                                    <th>Port</th>
                                    <th>Timeout</th>
                                    <th>Event</th>
                                    <th>Notify</th>
                                    <th>Action</th>
                                </tr>
                                </tfoot>
                            </table>
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