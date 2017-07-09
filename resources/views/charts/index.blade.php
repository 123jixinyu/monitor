@extends('adminlte::page')
@section('css')
@endsection
@section('content')
    <div class="content-wrapper" style="margin-left: 10px">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                监控报表
                <small>Version 1.0</small>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-desktop"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">稳定性</span>
                            <span class="info-box-number"><small>{{chart_totals('stable')}}</small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-warning"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">报警数</span>
                            <span class="info-box-number">{{chart_totals('errors')}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->

                <!-- fix for small devices only -->
                <div class="clearfix visible-sm-block"></div>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-tasks"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">监控数</span>
                            <span class="info-box-number">{{chart_totals('monitors')}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

                        <div class="info-box-content">
                            <span class="info-box-text">成员数</span>
                            <span class="info-box-number">{{chart_totals('members')}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">近{{config('monitor.day')}}天监控报表</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center">
                                        <strong>{{date('Y-m-d', strtotime("-" . config('monitor.day') . "day"))}}
                                            ～{{date('Y-m-d',time())}}</strong>
                                    </p>
                                    <div id="err-chart">
                                    </div>
                                    <!-- /.chart-responsive -->
                                </div>
                                <!-- /.col -->
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <!-- Main row -->
            <div class="row">
                <!-- Left col -->
                <div class="col-md-12">
                    <!-- TABLE: LATEST ORDERS -->
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">最近异常信息</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                            class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>监控类型</th>
                                        <th>监控地址</th>
                                        <th>监控端口</th>
                                        <th>通知组</th>
                                        <th>是否通知</th>
                                        <th>异常时间点</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($monitorLogs&&count($monitorLogs)>0)
                                        @foreach($monitorLogs as $monitorLog)
                                            <tr>
                                                <td>{{$monitorLog->monitor_type}}</td>
                                                <td>{{$monitorLog->host}}</td>
                                                <td>{{$monitorLog->port}}</td>
                                                <td>{{$monitorLog->group_name}}</td>
                                                <td>{{$monitorLog->getSend()}}</td>
                                                <td>{{$monitorLog->created_at}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="6">暂无信息</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>
                                <div class="pull-right">
                                    {!! $monitorLogs->render() !!}
                                </div>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                    </div>
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@section('js')
    <script src="{{asset('assets/js/highcharts.js')}}"></script>
    <script>
        var chart = new Highcharts.Chart('err-chart', {
            title: {
                text: '监控报表(按天)'
            },
            xAxis: {
                categories:{!! $x_data !!}
            },
            yAxis: {
                title: {
                    text: '报警数 (次)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '次'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
                {
                    name: '全部',
                    data: {!! $y_data !!}
                }
            ]
        });

    </script>
@endsection