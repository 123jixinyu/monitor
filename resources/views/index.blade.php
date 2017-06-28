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
    <div class="content-wrapper" style="margin-left: 10px">
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
                                    <td>xxxx</td>
                                    <td>状态</td>
                                    <td>
                                        <a href='#'>编辑</a>
                                        <a href='#'>开启</a>
                                        <a href='#'>删除</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>MySQL</td>
                                    <td>127.0.0.1</td>
                                    <td>3306</td>
                                    <td>2s</td>
                                    <td>3次</td>
                                    <td>公司</td>
                                    <td>xxxx</td>
                                    <td>状态</td>
                                    <td>
                                        <a href='#'>编辑</a>
                                        <a href='#'>开启</a>
                                        <a href='#'>删除</a>
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
                                    <th>Remark</th>
                                    <th>Status</th>
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
                                    <select multiple class="form-control">
                                        <option>option 1</option>
                                        <option>option 2</option>
                                        <option>option 3</option>
                                        <option>option 4</option>
                                        <option>option 5</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">地址</label>
                                    <input type="email" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="required">端口</label>
                                    <input type="email" class="form-control" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="required">请求超时设置</label>
                                    <select class="form-control">
                                        <option>1秒</option>
                                        <option>2秒</option>
                                        <option>3秒</option>
                                        <option>4秒</option>
                                        <option>5秒</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">触发事件</label>
                                    <select class="form-control">
                                        <option>1次</option>
                                        <option>2次</option>
                                        <option>3次</option>
                                        <option>4次</option>
                                        <option>5次</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="required">通知组</label><span class="pull-right">没有通知组？<a
                                                href="#">去创建</a></span>
                                    <select class="form-control">
                                        <option>通知组1</option>
                                        <option>通知组2</option>
                                        <option>通知组3</option>
                                        <option>通知组4</option>
                                        <option>通知组5</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>备注</label>
                                    <input type="text" class="form-control" placeholder="请输入备注">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> 是否立即开启
                                    </label>
                                </div>
                            </div>
                            <!-- /.box-body -->
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary">保存</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@endsection
@section('js')
    <script>
        var app = new Vue({
            el: '#monitor-edit',
            data: {
                monitor_types:''
            },
            mounted:function(){
                _this=this;
                _this.monitor_types=_this.$http.get('get_monitor_types').then(function(response){
                    _this.monitor_types=response.data.data;
                });
            }

        })
    </script>
@endsection