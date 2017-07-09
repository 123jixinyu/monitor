@section('css')
    <style>

        .login-page, .register-page {
            /*background: #ffffff;*/
            background-image: url('{{asset('assets/images/back.png')}}');
        }

        .login-box-msg {
            color: #313131;
        }

        .btn-info,
        .btn-info.disabled {
            background: #2cabe3;
            border: 1px solid #2cabe3;
        }

        .btn-rounded {
            border-radius: 60px;
        }
        .login-logo b{
            color:#777777;
        }
    </style>
@endsection
@extends('adminlte::login')
@section('js')
    <script src="{{asset('assets/js/vector.js')}}"></script>
    <script>
    </script>
@endsection