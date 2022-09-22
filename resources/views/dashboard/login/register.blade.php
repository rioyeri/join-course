<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <title>Sign Up | Flash Academia</title>

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
            background : rgba(255,255,255,0.8);
            margin: 12% auto;
            padding: 50px 0;
            color: #000;
            box-shadow: 0 0 20px 2px rgba(255,255,255,0.5)
        }

        @media (max-width: 991px) {
            .form-box {
                width:95%;
                background : rgba(255,255,255,0.8);
                margin: 7% auto;
                padding: 50px 0;
                color: #000;
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
            margin-top: 8px;
            margin-left: 30px;
            cursor: pointer;
        }

        .username-error {
            position: absolute;
            margin-top: 8px;
            margin-left: 10px;
            /* margin-left: -105px; */
            color: red;
            font-weight: 700;
        }

        .input-box {
            margin : 31px auto;
            width: 90%;
            border-bottom: 1px solid #000;
            padding-top: 10px;
            padding-bottom: 5px;
        }

        .input-box input{
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #000;
        }

        .input-box-error {
            margin : 31px auto;
            width: 90%;
            border-bottom: 1px solid red;
            padding-top: 10px;
            padding-bottom: 5px;
        }

        .input-box-error input{
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: red;
        }

        .input-checkbox {
            margin : 31px auto;
            width: 90%;
            padding-top: 10px;
            padding-bottom: 5px;
        }

        ::placeholder{
            color: #444;
        }

        a {
            color: #f85a40;
        }
        a:hover {
            color: #f82;
        }
    </style>
</head>

<body>
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <div id="login-page">
        <div class="container">
            <form class="form-box" autocomplete="off" role="form" action="{{ route('post_register') }}" method="POST">
                @csrf
                <h2>Sign Up</h2>

                <div class="login-wrap">
                    <div class="form-inline input-box">
                        <input type="email" name="email" id="email" placeholder="Email" autofocus>
                    </div>
                    <div class="form-inline input-box" id="username-box">
                        <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" onkeyup="checkUniqueUsername()" onchange="checkUniqueUsername()">
                        <span class="username-error" id="uname-error" style="display: none;">Username has been taken</span>
                    </div>
                    <div class="form-inline input-box">
                        <input type="text" name="name" id="name" placeholder="Fullname">
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password">
                        <span class="eye" onclick="showpass('pass1')">
                            <i class="fa fa-eye-slash" id="togglePassword1"></i>
                        </span>
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" name="password_retype" id="password_retype" placeholder="Confirm Password">
                        <span class="eye" onclick="showpass('pass2')">
                            <i class="fa fa-eye-slash" id="togglePassword2"></i>
                        </span>
                    </div>
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
                    <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-user"></i> Sign Up</button>
                </div>
                <h5 class="text-center">
                    Already have account? <a href="{{ route('get_login') }}"> Sign In</a>
                </h5>
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

        $("input#username").on({
            keydown: function(e) {
                if (e.which === 32)
                return false;
            },
            change: function() {
                this.value = this.value.replace(/\s/g, "");
            }
        });
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

        function checkUniqueUsername(){
            var username = $('#username').val();
            $.ajax({
                url: "{{ route('checkUsernameAvailability') }}",
                type: "get",
                dataType: 'json',
                data: {
                    "username": username,
                },success:function(data){
                    console.log(data.text);
                    if(data.text == "notavailable"){
                        document.getElementById("username-box").classList.remove('input-box');
                        document.getElementById("username-box").classList.add('input-box-error');
                        if(document.getElementById("uname-error").style.display != "block"){
                            document.getElementById("uname-error").style.display = "block";                            
                        }
                    }else{
                        document.getElementById("username-box").classList.remove('input-box-error');
                        document.getElementById("username-box").classList.add('input-box');
                        if(document.getElementById("uname-error").style.display != "none"){
                            document.getElementById("uname-error").style.display = "none";                            
                        }
                    }
                }
            })
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
