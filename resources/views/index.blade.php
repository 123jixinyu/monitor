@extends('adminlte::page')
@section('css')
    <link href="{{asset('assets/css/style.css')}}" rel="stylesheet"/>
@endsection
@section('content')

    <section id="introduction">
        <h2 class="page-header"><a href="#introduction">FullMonitor简介</a></h2>
        <p class="lead">
            <b>FullMonitor</b> 致力于监控服务器运行状态,作为一个开发人员，你是否会有这样的担心:
        </p>
    </section><!-- /#introduction -->


    <!-- ============================================================= -->

    <section id="download">
        <h2 class="page-header"><a href="#download">Download</a></h2>
        <p class="lead">
            AdminLTE can be downloaded in two different versions, each appealing to different skill levels and use case.
        </p>
        <div class="row">
            <div class="col-sm-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Ready</h3>
                        <span class="label label-primary pull-right"><i class="fa fa-html5"></i></span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <p>Compiled and ready to use in production. Download this version if you don't want to customize AdminLTE's LESS files.</p>
                        <a href="http://almsaeedstudio.com/download/AdminLTE-dist" class="btn btn-primary"><i class="fa fa-download"></i> Download</a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-sm-6">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Source Code</h3>
                        <span class="label label-danger pull-right"><i class="fa fa-database"></i></span>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <p>All files including the compiled CSS. Download this version if you plan on customizing the template. <b>Requires a LESS compiler.</b></p>
                        <a href="http://almsaeedstudio.com/download/AdminLTE" class="btn btn-danger"><i class="fa fa-download"></i> Download</a>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
  <pre class="hierarchy bring-up"><code class="language-bash" data-lang="bash">File Hierarchy of the Source Code Package

AdminLTE/
├── dist/
│   ├── CSS/
│   ├── JS
│   ├── img
├── build/
│   ├── less/
│   │   ├── AdminLTE's Less files
│   └── Bootstrap-less/ (Only for reference. No modifications have been made)
│       ├── mixins/
│       ├── variables.less
│       ├── mixins.less
└── plugins/
    ├── All the customized plugins CSS and JS files</code></pre>
    </section>


    <!-- ============================================================= -->

    <section id="dependencies">
        <h2 class="page-header"><a href="#dependencies">Dependencies</a></h2>
        <p class="lead">AdminLTE depends on two main frameworks.
            The downloadable package contains both of these libraries, so you don't have to manually download them.</p>
        <ul class="bring-up">
            <li><a href="http://getbootstrap.com" target="_blank">Bootstrap 3</a></li>
            <li><a href="http://jquery.com/" target="_blank">jQuery 1.11+</a></li>
            <li><a href="#plugins">All other plugins are listed below</a></li>
        </ul>
    </section>


    <!-- ============================================================= -->

    <section id="advice">
        <h2 class="page-header"><a href="#advice">A Word of Advice</a></h2>
        <p class="lead">
            Before you go to see your new awesome theme, here are few tips on how to familiarize yourself with it:
        </p>

        <ul>
            <li><b>AdminLTE is based on <a href="http://getbootstrap.com/" target="_blank">Bootstrap 3</a>.</b> If you are unfamiliar with Bootstrap, visit their website and read through the documentation. All of Bootstrap components have been modified to fit the style of AdminLTE and provide a consistent look throughout the template. This way, we guarantee you will get the best of AdminLTE.</li>
            <li><b>Go through the pages that are bundled with the theme.</b> Most of the template example pages contain quick tips on how to create or use a component which can be really helpful when you need to create something on the fly.</li>
            <li><b>Documentation.</b> We are trying our best to make your experience with AdminLTE be smooth. One way to achieve that is to provide documentation and support. If you think that something is missing from the documentation, please do not hesitate to create an issue to tell us about it.</li>
            <li><b>Built with <a href="http://lesscss.org/" target="_blank">LESS</a>.</b> This theme uses the LESS compiler to make it easier to customize and use. LESS is easy to learn if you know CSS or SASS. It is not necessary to learn LESS but it will benefit you a lot in the future.</li>
            <li><b>Hosted on <a href="https://github.com/almasaeed2010/AdminLTE/" target="_blank">GitHub</a>.</b> Visit our GitHub repository to view issues, make requests, or contribute to the project.</li>
        </ul>
        <p>
            <b>Note:</b> LESS files are better commented than the compiled CSS file.
        </p>
    </section>


@endsection
@section('js')
@endsection