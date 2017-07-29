@section('css')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/particle.css')}}"/>
    <style>

        .login-page, .register-page {
            /*background: #ffffff;*/
            background-color: #605CA8;
        }

        .login-box-msg {
            color: #313131;
        }

        .btn-info, .btn-info.disabled {
            background:#6C67B9;
            border: 1px solid #2cabe3;
        }
        .btn-info:hover{
            background:#605CA8;
        }

        .btn-rounded {
            border-radius: 60px;
        }
        /*.login-logo b{*/
            /*color:#777777;*/
        /*}*/
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            background-color:#605CA8;
            background-image: url("");
            background-repeat: no-repeat;
            background-size: cover;
            top:0;
        }
        .login-box{
            position: relative;
        }
        .icheckbox_square-blue, .iradio_square-blue{
            background: url('{{asset('assets/images/purple.png')}}') no-repeat;
        }
    </style>
@endsection
@extends('adminlte::login')
@section('js')
    <script src="{{asset('assets/js/vector.js')}}"></script>
    <script src="{{asset('assets/js/particles.min.js')}}"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 380,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    },
                    "image": {
                        "src": "img/github.svg",
                        "width": 100,
                        "height": 100
                    }
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.4,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 6,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });


    </script>
@endsection