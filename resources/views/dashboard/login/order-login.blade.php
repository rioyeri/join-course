<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <title>Sign In | Flash Academia</title>

    <!-- Favicons -->
    <link href="{{ asset('dashboard/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('dashboard/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('dashboard/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('dashboard/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/style-responsive.css') }}" rel="stylesheet">

    <!-- Notification css (Toastr) -->
    <link href="{{ asset('dashboard/additionalplugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- =======================================================
        Template Name: Dashio
        Template URL: https://templatemag.com/dashio-bootstrap-admin-template/
        Author: TemplateMag.com
        License: https://templatemag.com/license/
    ======================================================= -->

    <style>
        body{
            background-image: url(../../dashboard/img/blog-bg.jpg);
            background-size: cover;
            background-position: center;
        }
        .form-box {
            width:600px;
            background : rgba(0,0,0,0.8);
            margin: 12% auto;
            padding: 50px 0;
            color: #fff;
            box-shadow: 0 0 20px 2px rgba(0,0,0,0.5)
        }

        @media (max-width: 991px) {
            .form-box {
                width:95%;
                background : rgba(0,0,0,0.8);
                margin: 7% auto;
                padding: 50px 0;
                color: #fff;
                box-shadow: 0 0 20px 2px rgba(0,0,0,0.5)
            }

            .eye {
                position: absolute;
                margin-top: 3px;
                margin-right: 10px;
                /* margin-left: 30px; */
            }
        }

        h2{
            text-align:center;
            margin-bottom:40px;
        }
        .eye {
            position: absolute;
            margin-top: 3px;
            margin-left: 30px;
            cursor: pointer;
        }

        .input-box {
            margin : 31px auto;
            width: 90%;
            border-bottom: 1px solid #fff;
            padding-top: 10px;
            padding-bottom: 5px;
        }

        .input-box input{
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #fff;
        }

        .input-checkbox {
            margin : 31px auto;
            width: 90%;
            padding-top: 10px;
            padding-bottom: 5px;
        }

        ::placeholder{
            color: #ccc;
        }
        
        a {
            /* color: #f85a40; */
            color: var(--color-secondary);
        }
        a:hover {
            /* color: #f82; */
            color: var(--color-secondary-bright);
        }

        .btn-google-login {
            color: #fff;
            background: #f85a40;
            width:95%;
            margin: 12% auto;
        }
    </style>
</head>

<body>
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <div id="login-page">
        <div class="container">
            <form class="form-box" id="form" role="form" action="{{route('Login')}}" method="POST">
                @csrf
                <h2>Sign In</h2>
                <input type="hidden" name="data" id="data" value="{{ $data }}">
                <input type="hidden" name="order" id="order" value="{{ $order }}">
                <div class="login-wrap">
                    <div class="form-inline input-box">
                        <input type="text" name="login_id" id="login_id" placeholder="Username / Email" autofocus autocomplete="new-password">
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password">
                        <span class="eye" onclick="showpass()">
                            <i class="fa fa-eye-slash" id="togglePassword"></i>
                        </span>
                    </div>
                    <span class="pull-right">
                        <a data-toggle="modal" href="#myModal"> Forgot Password?</a>
                    </span>
                    <br>
                    <br><br>
                    <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-unlock"></i> Sign In</button>
                </div>
                <h5 class="text-center">
                    Don't have an account? <a href="{{ route('get_register_to_order',['data'=> $data, 'order'=> $order]) }}"> Sign Up</a>
                </h5>
                <h4 class="text-center">
                    <a href="{{ route('google.redirect',['data'=> $data, 'order'=> $order]) }}" class="btn btn-google-login btn-block" id="button-google"><i class="fa fa-google-plus" style="margin-left:25px"></i> Login with Google</a>
                </h4>
                <h5 class="text-center">
                    <a href="{{ route('getHome') }}"  style="color:#fff;"><i class="fa fa-home"></i> Back to <strong class="back-to-homepage"> Homepage</strong></a>
                </h5>
            </form>
            <form action="{{route('forgotpassword')}}" method="POST">
                @csrf
                <!-- Modal -->
                <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Forgot Password ?</h4>
                            </div>
                            <div class="modal-body">
                                <p>Enter your e-mail address below to reset your password.</p>
                                <input type="text" name="email" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">
                            </div>
                            <div class="modal-footer">
                                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                                <button class="btn btn-theme" type="submit">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- modal -->
            </form>
        </div>
    </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ asset('dashboard/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    
    <!-- Toastr js -->
    <script src="{{ asset('dashboard/additionalplugins/toastr/toastr.min.js') }}"></script>
    @include('dashboard.layout.js')
    <script>
        // $.backstretch("../../dashboard/img/login-bg.jpg", {
        //     speed: 500
        // });

        function showpass(){
            var pass = $("#password").attr('type');
            var eye = document.getElementById('togglePassword');
            var id="password";

            if(pass == "password"){
                eye.classList.add("fa-eye");
                eye.classList.remove("fa-eye-slash");
                document.getElementById(id).type="text";
            }else{
                eye.classList.add("fa-eye-slash");
                eye.classList.remove("fa-eye");
                document.getElementById(id).type="password";
            }
        }
    </script>
</body>

</html>
