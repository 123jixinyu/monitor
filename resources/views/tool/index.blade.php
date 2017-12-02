@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>ping</h3>

                    <p>利用"ping"命令可以检查网络是否连通。</p>
                </div>
                <div class="icon">
                    <i class="fa fa-connectdevelop"></i>
                </div>
                <a href="{{route('tools',['type'=>'ping'])}}" class="small-box-footer">
                    去使用 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>Tracert</h3>

                    <p>确定"IP"数据包访问目标所采取的路径。</p>
                </div>
                <div class="icon">
                    <i class="fa fa-meanpath"></i>
                </div>
                <a href="{{route('tools',['type'=>'tracert'])}}" class="small-box-footer">
                    去使用 <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

    </div>
@endsection
@section('js')
@endsection