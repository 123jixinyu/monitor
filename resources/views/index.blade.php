@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')

    <section id="introduction">
        <h2 class="page-header"><a href="#introduction">91 Monitor简介</a></h2>
        <p class="lead">
            <b>91 Monitor</b> 系统致力于监控服务器运行状态,作为一个开发人员，你是否会有这样的担心:我上线后的项目如果服务器挂了怎么办？如果mysql崩了怎么办？如果。。。。，这个时候你会想到如何监控服务器的运行状态，91 Monitor就是为你量身打造的服务器监控系统。
        </p>
    </section><!-- /#introduction -->


    <!-- ============================================================= -->

    <section id="download">
        <h2 class="page-header"><a href="#download">下载</a></h2>
        <p class="lead">
            91 Monitor现在提供两种下载方式，压缩包下载以及源码安装,其中压缩包为已经压缩好的项目，无需安装，但是建议使用源码安装。
        </p>
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">压缩包</h3>
                        <span class="label label-primary pull-right"><i class="fa fa-html5"></i></span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <p>所有的文件已经打包好，只需要简单配置即可运行</p>
                        <a href="#" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">源码</h3>
                        <span class="label label-danger pull-right"><i class="fa fa-database"></i></span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <p>需要git克隆后，然后安装所需依赖。（建议）</p>
                        <a href="https://github.com/123jixinyu/monitor" class="btn btn-danger"><i class="fa fa-download"></i> Download</a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
  <pre class="hierarchy bring-up"><code class="language-bash" data-lang="bash">代码目录结构

app目录包含了应用的核心代码；

bootstrap目录包含了少许文件用于框架的启动和自动载入配置，还有一个cache文件夹用于包含框架生成的启动文件以提高性能；

config目录包含了应用所有的配置文件；

database目录包含了数据迁移及填充文件，如果你喜欢的话还可以将其作为SQLite数据库存放目录；

public目录包含了前端控制器和资源文件（图片、js、css等）；

resources目录包含了视图文件及原生资源文件（LESS、SASS、CoffeeScript），以及本地化文件；

storage目录包含了编译过的Blade模板、基于文件的session、文件缓存，以及其它由框架生成的文件，该文件夹被隔离成app、framework和logs目录，app目录用于存放应用要使用的文件，framework目录用于存放框架生成的文件和缓存，最后，logs目录包含应用的日志文件；

tests目录包含自动化测试，其中已经提供了一个开箱即用的PHPUnit示例；

vendor目录包含Composer依赖；</code></pre>
    </section>


    <!-- ============================================================= -->

    <section id="dependencies">
        <h2 class="page-header"><a href="#dependencies">依赖</a></h2>
        <p class="lead">91 Monitor主要依赖四个主要的框架，这些框架代码会在解压或者composer的时候一同下载，不需要独立安装。</p>
        <ul class="bring-up">
            <li><a href="https://laravel.com/">Laravel 5.1</a></li>
            <li><a href="https://adminlte.io/" target="_blank">Adminlte</a></li>
            <li><a href="http://getbootstrap.com" target="_blank">Bootstrap 3</a></li>
            <li><a href="https://cn.vuejs.org/" target="_blank">Vue 2</a></li>
            <li><a href="http://jquery.com/" target="_blank">jQuery 1.11+</a></li>
        </ul>
    </section>

    <section id="install">
        <h2 class="page-header"><a href="#install">安装说明</a></h2>
        <ul class="bring-up">
            <li>1.克隆 git clone git@github.com:123jixinyu/monitor.git</li>
            <li>2.安装依赖composer install</li>
            <li>3.拷贝 .env.example到.env,并修改.env配置，建立相应数据库</li>
            <li>4.运行php artisan migrate初始化表，然后运行php artisan key:generate,生成key</li>
            <li>5.运行composer dump-autoload  然后再运行php artisan db:seed初始化数据</li>
            <li>6.配置nginx映射到public 目录下，并且设置storage以及bootstrap目录权限。在public 目录下创建名为uploads的文件夹并赋予写入权限</li>
            <li>7.将/usr/bin/php xxxxx/monitor/artisan schedule:run 加到crontab中去</li>
        </ul>
    </section>
    <!-- ============================================================= -->
@endsection
@section('js')
@endsection