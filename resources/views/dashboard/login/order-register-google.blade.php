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
    <link href="{{ asset('dashboard/img/favicon.ico') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('dashboard/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('dashboard/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('dashboard/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/style-responsive.css') }}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
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
            margin: 2% auto;
            padding: 50px 0;
            color: #fff;
            box-shadow: 0 0 20px 2px rgba(0,0,0,0.5);
        }

        @media (max-width: 991px) {
            .form-box {
                width:95%;
                background : rgba(255,255,255,0.8);
                margin: 7% auto;
                padding: 50px 0;
                color: #000;
                box-shadow: 0 0 20px 2px rgba(0,0,0,0.5);
            }

            .eye {
                position: absolute;
                margin-top: 3px;
                padding-right: 50px;
                /* margin-left: 30px; */
            }

            .select-box select{
                width: 90%;
                border: none;
                outline: none;
                background: transparent;
                color: #fff;
            }

            .select-box .col-md-3 label {
                margin-top:10%;
                margin-left:-2%;
                width: 90%;
                text-align: left;
            }

            .select-box .col-md-3 select {
                width: 90%;
                border-bottom: 1px solid #fff;
                margin-left: 1%;
                margin-bottom: 5%;
            }

            .input-checkbox {
                margin : 31px auto;
                width: 90%;
                /* padding-top: 20px; */
                /* padding-bottom: 5px; */
            }

            .input-checkbox .col-md-3 label {
                text-align: center;
                padding-left: 12%;
                /* margin-right: -15%; */
            }

            .mix-box .col-md-6 input {
                width: 85%;
                border-bottom: 1px solid #000;
                margin-bottom: 5%;
            }

            .mix-box .col-md-6 select {
                width: 90%;
                border-bottom: 1px solid #000;
                margin-left:1%;
                margin-top:5%;
            }
        }

        input {
            font-size: 14px;
        }

        label {
            font-size: 14px;
        }

        .select2 {
            font-size: 14px;
            height: 20px;
        }

        h2{
            text-align:center;
            margin-bottom:10px;
        }
        .eye {
            position: absolute;
            margin-top: 8px;
            margin-left: 30px;
            cursor: pointer;
        }

        .phone-error {
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
            padding-top: 30px;
            padding-bottom: 5px;
        }

        .input-checkbox .col-md-3 label {
            text-align: left;
            margin-left: -18%;
            margin-right: -15%;
        }

        .select-box {
            margin : 31px auto;
            /* padding-top: 10px; */
            padding-bottom: 5px;
        }

        .select-box .select2 {
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #fff;
            margin-top:10px;
        }

        .select-box label {
            margin-left:10%;
            width: 90%;
        }

        .select-box .col-md-3 .select2 {
            width: 100%;
            border-bottom: 1px solid #fff;
        }

        .mix-box {
            margin : 31px auto;
            padding-top: 15px;
            padding-bottom: 5px;
        }

        .mix-box input {
            /* margin-left:10%; */
            width: 90%;
            border:  none;
            outline: none;
            background: transparent;
            color: #000;
        }

        .mix-box .select2{
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #000;
            /* margin-left: 5%; */
            margin-left: -15px;
            margin-top:2.9px;
        }

        .mix-box .col-md-6 input {
            width: 100%;
            border-bottom: 1px solid #000;
            margin-left: 3%;
        }

        .mix-box .col-md-6 .select2 {
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .select2 option {
            background: rgba(0,0,0,0.8);
        }

        ::placeholder{
            color: #ccc;
        }
    </style>
    <!-- Google tag (gtag.)9)-->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-Y4KW53C62C"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-Y4KW53C62C');
    </script>
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

                <input type="text" name="data" id="data" value="{{ $data }}">
                <input type="text" name="order" id="order" value="{{ $order }}">

                <div class="login-wrap">
                    <div class="form-inline input-box" id="phone-box">
                        <input type="text" name="phonenumber" id="phonenumber" placeholder="Whatsapp Number" autocomplete="off"  autofocus>
                        <span class="phone-error" id="phone-error" style="display: none;">Phone Number Invalid</span>
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" name="password" id="password" placeholder="Make a Password" autocomplete="off">
                        <span class="eye" onclick="showpass('pass1')">
                            <i class="fa fa-eye-slash" id="togglePassword1"></i>
                        </span>
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" id="password_retype" placeholder="Confirm Password">
                        <span class="eye" onclick="showpass('pass2')">
                            <i class="fa fa-eye-slash" id="togglePassword2"></i>
                        </span>
                    </div>
                    <div class="form-inline select-box">
                        {{-- <input type="text" name="birthdate" id="birthdate" placeholder="Confirm Password"> --}}
                        <div class="col-md-3">
                            <label style="margin-top:10%"><b>Date of Birth : </b></label>
                        </div>
                        <div class="col-md-3">
                            <select class="select2 col-md-6" parsley-trigger="change" name="birthdate_month" id="birthdate_month">
                                <option value="#" disabled selected>Month</option>
                                @for ($i=1; $i <= 12; $i++)
                                    <option value="{{$i}}">{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2 col-md-3" parsley-trigger="change" name="birthdate_date" id="birthdate_date">
                                <option value="#" disabled selected>Date</option>
                                @for ($i=1; $i <= 31; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2 col-md-3" parsley-trigger="change" name="birthdate_year" id="birthdate_year">
                                <option value="#" disabled selected>Year</option>
                                @for ($i=1950; $i <= date('Y'); $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="form-inline input-checkbox">
                        <div class="col-md-3">
                            <label style="margin-top:2px"><b>Your Role : </b></label>
                        </div>
                        @foreach ($roles as $key => $role)
                            <div class="col-md-2">
                                <div class="radio">
                                    <label><input type="radio" name="optionsRadios" id="options-{{ $role->id }}" value="{{ $role->id }}" @if($key == 0) checked @endif onchange="checkRole(this.value)"> {{ $role->name }} </label>
                                </div>
                            </div>                            
                        @endforeach
                    </div>
                    <br>
                    <div id="student-field">
                        <div class="form-inline input-box">
                            <div class="col-md-6">
                                <input type="text" name="school_name" id="school_name" placeholder="School Name">
                            </div>
                            <div>
                                <select class="form-control select2" parsley-trigger="change" name="school_grade" id="school_grade">
                                    <option value="#" disabled selected>-- Grade --</option>
                                    @foreach ($grades as $grade)
                                        <option value="{{$grade->id}}" >{{$grade->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
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
    <!-- Select2 -->
    <script src="{{ asset('dashboard/additionalplugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
    
    <!-- Toastr js -->
    <script src="{{ asset('dashboard/additionalplugins/toastr/toastr.min.js') }}"></script>
    <script>
        // $.backstretch("../../dashboard/img/login-bg.jpg", {
        //     speed: 500
        // });

        $('.select2').select2();


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

        function checkRole(id){
            // console.log(id);
            if(id == 4){
                document.getElementById('student-field').style.display = 'block';
            }else{
                document.getElementById('student-field').style.display = 'none';
            }
        }

        $("form").submit(function(event){
            password = $('#password').val();
            retype = $('#password_retype').val();
            

            if(password != retype){
                // document.getElementById("checkpassword").style.display = 'block';
                toastr.error("Konfirmasi Password gagal!", 'Gagal!')
                $('#password').val("");
                $('#password_retype').val("");
                event.preventDefault();
                return false;
            }else{
                document.getElementById("form").submit();
            }
        });
    </script>
</body>

</html>
