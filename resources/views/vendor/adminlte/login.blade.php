@extends('adminlte::master')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
@stop

@section('body_class', 'login-page')

@section('body')
    <div id="particles-js" style="top: 0px;">
    </div>
    <div id="login-container">
        <div id="login-output">
            <div class="login-box">
                <div class="login-logo">
                    <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
                </div>
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
                    <form action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                        {!! csrf_field() !!}

                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label>邮箱</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                   placeholder="{{ trans('adminlte::adminlte.email') }}">
                            <span class="glyphicon glyphicon-envelope form-control-feedback" style="top:15px"></span>
                            @if ($errors->has('email'))
                                <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <label>密码</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="{{ trans('adminlte::adminlte.password') }}">
                            <span class="glyphicon glyphicon-lock form-control-feedback" style="top:15px"></span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-xs-8">
                                <div class="checkbox icheck">
                                    <label>
                                        <input type="checkbox" name="remember"> {{ trans('adminlte::adminlte.remember_me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="checkbox">
                                    <label>
                                        <a href="{{ url(config('adminlte.password_reset_url', 'password/email')) }}"
                                           class="text-center"
                                        >{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <button type="submit"
                                        class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light">{{ trans('adminlte::adminlte.sign_in') }}</button>
                            </div>
                        </div>
                    </form>
                    <div class="auth-links">
                        <div class="col-xs-offset-4">
                            @if (config('adminlte.register_url', 'register'))
                                没有账号？
                                <a href="{{ url(config('adminlte.register_url', 'register')) }}"
                                   class="text-center"
                                >{{ trans('adminlte::adminlte.register_a_new_membership') }}</a>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.login-box-body -->
            </div><!-- /.login-box -->
        </div>
    </div>

@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('js')
@stop
