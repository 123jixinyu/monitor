@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet"
          href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} ">
    @stack('css')
    @yield('css')
@stop

@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' . (config('adminlte.layout') ? [
    'boxed' => 'layout-boxed',
    'fixed' => 'fixed',
    'top-nav' => 'layout-top-nav'
][config('adminlte.layout')] : '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))

@section('body')
    <div class="wrapper">

        <!-- Main Header -->
        <header class="main-header">
            @if(config('adminlte.layout') == 'top-nav')
                <nav class="navbar navbar-static-top">
                    <div class="container">
                        <div class="navbar-header">
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                                {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                            </a>
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#navbar-collapse">
                                <i class="fa fa-bars"></i>
                            </button>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                            <ul class="nav navbar-nav">
                                @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                            </ul>
                        </div>
                        <!-- /.navbar-collapse -->
                    @else
                        <!-- Logo -->
                            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                                <!-- mini logo for sidebar mini 50x50 pixels -->
                                <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                                <!-- logo for regular state and mobile devices -->
                                <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
                            </a>

                            <!-- Header Navbar -->
                            <nav class="navbar navbar-static-top" role="navigation">
                                <!-- Sidebar toggle button-->
                                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                                </a>
                            @endif
                            <!-- Navbar Right Menu -->
                                <div class="navbar-custom-menu">

                                    <ul class="nav navbar-nav" id="nav-bar-menu">

                                        <!-- Notifications: style can be found in dropdown.less -->
                                        <li class="dropdown notifications-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="fa fa-bell-o"></i>
                                                <span class="label label-warning">{{get_confirm_times()}}</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li class="header">你当前有{{get_confirm_times()}}个报警信息</li>
                                                <li class="footer"><a href="{{route('chart_index').'#recently-error-information'}}"
                                                                      v-on:click="confirm">查看</a></li>
                                            </ul>
                                        </li>
                                        <!-- Tasks: style can be found in dropdown.less -->
                                        {{--user-acount--}}
                                        <li class="dropdown user user-menu">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <img src="{{user_avatar()}}" class="user-image" alt="User Image">
                                                <span class="hidden-xs">{{user_name()}}</span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <!-- User image -->
                                                <li class="user-header">
                                                    <img src="{{user_avatar()}}" class="img-circle" alt="User Image">

                                                    <p>
                                                        {{user_name()}}
                                                        <small>{{Auth::user()->experience}}</small>
                                                        <small>注册日期:{{Auth::user()->created_at}}</small>
                                                    </p>
                                                </li>
                                            </ul>
                                        </li>

                                        {{--logout--}}
                                        <li>
                                            @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION, '5.3.0', '<'))
                                                <a href="{{ url(config('adminlte.logout_url', 'auth/logout')) }}">
                                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                                </a>
                                            @else
                                                <a href="#"
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                                >
                                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                                </a>
                                                <form id="logout-form"
                                                      action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}"
                                                      method="POST" style="display: none;">
                                                    @if(config('adminlte.logout_method'))
                                                        {{ method_field(config('adminlte.logout_method')) }}
                                                    @endif
                                                    {{ csrf_field() }}
                                                </form>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            @if(config('adminlte.layout') == 'top-nav')
                    </div>
                    @endif
                </nav>
        </header>

    @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">

                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    {{--siderbar-user-panel--}}
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{user_avatar()}}" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>{{user_name()}}</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <ul class="sidebar-menu">
                        @each('adminlte::partials.menu-item', $adminlte->menu(), 'item')
                    </ul>
                    <!-- /.sidebar-menu -->
                </section>
                <!-- /.sidebar -->
            </aside>
    @endif

    <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
                <div class="container">
                @endif

                <!-- Content Header (Page header) -->
                    <section class="content-header">
                        @yield('content_header')
                    </section>

                    <!-- Main content -->
                    <section class="content">

                        @yield('content')

                    </section>
                    <!-- /.content -->
                    @if(config('adminlte.layout') == 'top-nav')
                </div>
                <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->
        <div id="dialog" v-bind:class="['modal',is_show ? 'show':'fade']" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" v-text="dialog_title"></h4>
                    </div>
                    <div class="modal-body">
                        <p v-text="dialog_message"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" v-on:click="onClick($event)">
                            关闭
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/app.min.js') }}"></script>
    <script src="{{asset('assets/js/vue/vue.js')}}"></script>
    <script src="{{asset('assets/js/vue/vue-resource.min.js')}}"></script>
    {{--@stack('js')--}}
    <script>
        var dialog = new Vue({
            el: '#dialog',
            data: {
                is_show: false,
                dialog_title: '',
                dialog_message: ''
            },
            methods: {
                onClick: function ($event) {
                    this.is_show = this.is_show ? false : true;
                },
                show: function (message) {
                    _this = this;
                    _this.dialog_title = '提示';
                    _this.dialog_message = message;
                    _this.is_show = true;
                }
            }
        });
        var nav = new Vue({
            el: '#nav-bar-menu',
            data: {},
            methods: {
                confirm: function () {
                    _this = this;
                    _this.$http.get('{{route('user_confirm')}}').then(function(res){});
                }
            }
        });
    </script>
    @yield('js')
@stop
