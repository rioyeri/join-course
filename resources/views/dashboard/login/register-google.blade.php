<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <title>Register | Flash Academia</title>

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

        h2{
            text-align:center;
            margin-bottom:40px;
        }
        .eye {
            position: absolute;
            margin-top: 8px;
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
    </style>
</head>

<body>
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <div id="login-page">
        <div class="container">
            <form class="form-box" role="form" action="{{ route('storePassword', ['id' => $user->id]) }}" method="POST">
                @csrf
                <h2>One Step Closer
                    <p class="small-text">You have try to login with <b>{{ $user->email }}<b> ( <a href="{{ route('Logout') }}" class="logout-google">Logout?</a>)</p>
                </h2>

                <div class="login-wrap">
                    <div class="form-inline input-box">
                        <input type="password" name="password" id="password" placeholder="Make a Password" autofocus>
                        <span class="eye" onclick="showpass('pass1')">
                            <i class="fa fa-eye-slash" id="togglePassword1"></i>
                        </span>
                    </div>
                    <br>
                    <div class="form-inline input-box">
                        <input type="password" id="password_retype" placeholder="Confirm Password">
                        <span class="eye" onclick="showpass('pass2')">
                            <i class="fa fa-eye-slash" id="togglePassword2"></i>
                        </span>
                    </div>
                    <br>
                    <div class="form-inline input-checkbox">
                        <div class="col-md-4">
                            <label style="margin-top:2px"><b>Your Role : </b></label>
                        </div>
                        @foreach ($roles as $key => $role)
                            <div class="col-md-2">
                                <div class="radio">
                                    <label><input type="radio" name="optionsRadios" id="options-{{ $role->id }}" value="{{ $role->id }}" @if($key == 0) checked @endif> {{ $role->role_name }} </label>
                                </div>
                            </div>                            
                        @endforeach
                    </div>
                    <br><br>
                    <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-long-arrow-right"></i> Next</button>
                </div>
            </form>
        </div>
    </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="{{ asset('dashboard/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('dashboard/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    
    <!-- Toastr js -->
    <script src="{{ asset('dashboard/additionalplugins/toastr/toastr.min.js') }}"></script>
    <script>
        // $.backstretch("../../dashboard/img/login-bg.jpg", {
        //     speed: 500
        // });

        function showpass(span_id){
            if(span_id == "pass1"){
                var pass = $("#password").attr('type');
                var eye = document.getElementById('togglePassword1');
                var id="password";
            }else{
                var pass = $("#password_retype").attr('type');
                var eye = document.getElementById('togglePassword2');
                var id="password_retype";
            }

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

        $("form").submit(function(){
            password = $('#password').val();
            retype = $('#password_retype').val();
            

            if(password != retype){
                // document.getElementById("checkpassword").style.display = 'block';
                toastr.error("Konfirmasi Password gagal!", 'Gagal!')
                $('#password').val("");
                $('#password_retype').val("");
                event.preventDefault();
            }else{
                document.getElementById("form").submit();
            }
        });
    </script>
</body>

</html>
