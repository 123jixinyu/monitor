@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')
    <!-- ============================================================= -->

    <section id="download">
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-danger">
                    <div class="box-body">
                        <p>请确保已经安装了wssh服务</p>
                        <a href="{{$url}}" class="btn btn-danger"  target="_blank"><i
                                    class="fa fa-terminal"></i> 打开终端</a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section>

    <section id="install">
        <h2 class="page-header"><a href="#install">wssh安装说明</a></h2>
        <ul class="bring-up">
            <li>安装必要软件:<br>$ sudo apt-get install git gcc python libevent-dev python-dev python-pip</li>
            <li>安装库:<br>$ sudo pip install gevent gevent-websocket paramiko flask</li>
            <li>安装wssh服务:<br>
                $ git clone https://github.com/aluzzardi/wssh.git<br/>
                $ cd wssh<br/>
                $ sudo python setup.py install
            </li>
            <li>运行:<br>
                $ wsshd<br>
                如果输出wsshd/0.1.0 running on 0.0.0.0:5000代表正常
            </li>
            <li>将wsshd加入到守护进程中去<br>
                推荐使用supervisor来将wsshd命令加入到守护进程<br>
            </li>
        </ul>
    </section>
    <!-- ============================================================= -->
@endsection
@section('js')
@endsection