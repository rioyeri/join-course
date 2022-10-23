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

    <!-- Select2 -->
    <link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Multiple Select -->
    <link href="{{ asset('dashboard/additionalplugins/multiselect/css/multi-select.css') }}"  rel="stylesheet" type="text/css" />
    <!-- Notification css (Toastr) -->
    <link href="{{ asset('dashboard/additionalplugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
        
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('dashboard/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--external css-->
    <link href="{{ asset('dashboard/lib/font-awesome/css/font-awesome.css') }}" rel="stylesheet" />
    
    <!-- Custom styles for this template -->
    <link href="{{ asset('dashboard/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('dashboard/css/style-responsive.css') }}" rel="stylesheet">

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
            margin: 2% auto;
            padding: 10px 0;
            color: #000;
            box-shadow: 0 0 20px 2px rgba(255,255,255,0.5);
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
                /* margin-left: -100px; */
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
            height: 25px;
        }

        h2{
            text-align:center;
            /* margin-bottom:10px; */
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

        .email-error {
            position: absolute;
            margin-top: 8px;
            margin-left: 10px;
            /* margin-left: -105px; */
            color: red;
            font-weight: 700;
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
            margin : 20px auto;
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
            padding-bottom: 10px;
        }

        .select-box .select2{
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #000;
            margin-top:5px;
        }

        .select-box label {
            margin-left:10%;
            width: 90%;
        }

        .select-box .col-md-3 .select2 {
            width: 100%;
            border-bottom: 1px solid #000;
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
            /* margin-top:10px; */
        }

        .mix-box .select2{
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #000;
            /* margin-left: 5%; */
            margin-left: -15px;
            /* margin-top:2.9px; */
        }

        .mix-box .col-md-6 input {
            width: 100%;
            border-bottom: 1px solid #000;
            margin-left: 3%;
            margin-top: 2px;
        }

        .mix-box .col-md-6 .select2 {
            width: 100%;
            border-bottom: 1px solid #000;
        }

        .mix-box2 {
            margin : 31px auto;
            width: 100%;
            border-bottom: 1px solid #000;
            padding-top: 40px;
            padding-bottom: 25px;
        }

        .mix-box2 .select2 {
            width: 100%;
            color: #000;
            margin-top:-30px;
            margin-left: 0px;
        }

        .mix-box3 {
            margin : 31px auto;
            /* padding-top: 10px; */
            padding-bottom: 10px;
        }

        .mix-box3 .select2 {
            width: 90%;
            border: none;
            outline: none;
            background: transparent;
            color: #000;
            margin-top:10px;
        }

        .mix-box3 label {
            margin-left:10%;
            width: 90%;
        }

        .mix-box3 .col-md-9 .select2 {
            width: 100%;
            border-bottom: 1px solid #000;
        }

        ::placeholder{
            color: #444;
            opacity: 0.7;
        }

        a {
            color: #f85a40;
        }
        a:hover {
            color: #f82;
        }

        .select2-container--default .select2-selection--single{
            background-color: rgba(0,0,0,0);
            border: rgba(0,0,0,0);
        }
    </style>
</head>

<body>
    @php
        use App\Models\Order;
        if($order && $data){
            $user = Order::getFormatData($data, $order);
        }
    @endphp

    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <div id="login-page">
        <div class="container">
            @isset($user->id)
            <form class="form-box" id="form" role="form" action="{{ route('storePassword', ['id' => $user->id]) }}" method="POST">
                <h2>One Step Closer
                    <p class="small-text">You have try to login with <b>{{ $user->email }}<b> ( <a href="{{ route('Logout') }}" class="logout-google">Logout?</a>)</p>
                </h2>
            @else
            <form class="form-box" id="form" role="form" action="{{ route('post_register') }}" method="POST">
                <h2>Sign Up</h2>
            @endisset

                @csrf
                <input type="hidden" name="data" id="data" value="{{ $data }}">
                <input type="hidden" name="order" id="order" value="{{ $order }}">

                <div class="login-wrap">
                    <div class="form-inline input-box" id="email-box">
                        <input type="email" name="email" id="email" placeholder="Email" autofocus required onchange="checkEmail(this.value)" value="@isset($user->email){{ $user->email }}@endisset" @isset($user->email) readonly @endisset>
                        <span class="email-error" id="email-error" style="display: none;">Email Invalid</span>
                    </div>
                    <div class="form-inline input-box" id="username-box">
                        <input type="text" name="username" id="username" placeholder="Username" autocomplete="off" onkeyup="checkUniqueUsername()" onchange="checkUniqueUsername()" value="@isset($user->username){{ $user->username }}@endisset" required>
                        <span class="username-error" id="uname-error" style="display: none;">Username has been taken</span>
                    </div>
                    <div class="form-inline input-box" id="phone-box">
                        <input type="text" name="phonenumber" id="phonenumber" placeholder="Whatsapp Number" autocomplete="off" required onchange="checkPhone(this.value)" value="@isset($user->phone){{ $user->phone }}@endisset">
                        <span class="phone-error" id="phone-error" style="display: none;">Phone Number Invalid</span>
                    </div>
                    <div class="form-inline input-box">
                        <input type="text" name="name" id="name" placeholder="Fullname" value="@isset($user->name){{ $user->name }}@endisset" required>
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" name="password" id="password" placeholder="Password" autocomplete="new-password" required>
                        <span class="eye" onclick="showpass('pass1')">
                            <i class="fa fa-eye-slash" id="togglePassword1"></i>
                        </span>
                    </div>
                    <div class="form-inline input-box">
                        <input type="password" name="password_retype" id="password_retype" placeholder="Confirm Password" required>
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
                            <select class="form-control select2 col-md-6" parsley-trigger="change" name="birthdate_month" id="birthdate_month" required>
                                <option value="#" disabled selected>Month</option>
                                @for ($i=1; $i <= 12; $i++)
                                    <option value="{{$i}}">{{date("F", mktime(0, 0, 0, $i, 10))}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control select2 col-md-3" parsley-trigger="change" name="birthdate_date" id="birthdate_date" required>
                                <option value="#" disabled selected>Date</option>
                                @for ($i=1; $i <= 31; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="select2 col-md-3" parsley-trigger="change" name="birthdate_year" id="birthdate_year" required style="width: 85%">
                                <option value="#" disabled selected>Year</option>
                                @for ($i=1950; $i <= date('Y'); $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="form-inline input-checkbox">
                        <div class="col-md-3">
                            <label style="margin-top:2px"><b>Your Role : </b></label>
                        </div>
                        @foreach ($roles as $key => $role)
                            <div class="col-md-3">
                                <div class="radio">
                                    <label><input type="radio" name="optionsRadios" id="options-{{ $role->id }}" value="{{ $role->id }}" @if($key == 0) checked @endif onchange="checkRole(this.value)"> {{ $role->name }} </label>
                                </div>
                            </div>                            
                        @endforeach
                    </div>
                    <div id="student-field">
                        <div class="form-inline mix-box">
                            <div class="col-md-6">
                                <input type="text" name="school_name" id="school_name" placeholder="School Name" value="@isset($user->school){{ $user->school }}@endisset">
                            </div>
                            <div class="col-md-6">
                                <select class="form-control select2" parsley-trigger="change" name="student_grade" id="student_grade">
                                    <option value="#" disabled selected>-- Grade --</option>
                                    @foreach ($grades as $grade)
                                        @isset($user->grade_id)
                                            @if ($user->grade_id == $grade->id)
                                                <option value="{{$grade->id}}" selected>{{$grade->name}}</option>
                                            @else
                                                <option value="{{$grade->id}}">{{$grade->name}}</option>
                                            @endif
                                        @else
                                            <option value="{{$grade->id}}">{{$grade->name}}</option>
                                        @endisset
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div id="teacher-field" style="display:none;">
                        <div class="form-inline mix-box">
                            <div class="col-md-3">
                                <label style="margin-top:2px"><b>What subject do you teach</b></label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control select2 select2-multiple" multiple="multiple" multiple parsley-trigger="change" name="teacher_subjects[]" id="teacher_subjects">
                                    {{-- <option value="#" disabled selected>-- What subject do you teach--</option> --}}
                                    @foreach ($courses as $course)
                                        <optgroup label="{{ $course->name }}">
                                            <option value="{{$course->id}}" >{{$course->name}}</option>
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-user"></i> Sign Up</button>
                </div>
                <h5 class="text-center">
                    Already have account? <a href="{{ route('get_login_to_order',['data'=> $data, 'order'=> $order]) }}"> Sign In</a>
                </h5>

                <h5 class="text-center">
                    <a href="{{ route('getHome') }}"  style="color:#000;"><i class="fa fa-home"></i> Back to <strong  style="color: #00b6a1; font-size:18px"> Homepage</strong></a>
                </h5>
            </form>
        </div>
    </div>


    @section ('js')
        <!-- Select2 -->
        <script src="{{ asset('dashboard/additionalplugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
        <!-- Multiple Select -->
        <script type="text/javascript" src="{{ asset('dashboard/additionalplugins/multiselect/js/jquery.multi-select.js') }}"></script>    
    @endsection
    @section('script-js')
        <script>
            $('.select2').select2({
                // theme: "themes-dark",
            });


            $('#teacher_subjects').select2({
                // theme: "themes-dark",
                width: "100%",
                height: "30%",
            });

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

            function checkRole(id){
                // console.log(id);
                if(id == 4){
                    document.getElementById('student-field').style.display = 'block';
                    document.getElementById('teacher-field').style.display = 'none';
                }else if(id == 5){
                    document.getElementById('student-field').style.display = 'none';
                    document.getElementById('teacher-field').style.display = 'block';
                }
            }

            const validateEmail = (email) => {
                return email.match(
                    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                );
            };

            function checkEmail(email){
                if (!validateEmail(email)) {
                    document.getElementById("email-box").classList.remove('input-box');
                    document.getElementById("email-box").classList.add('input-box-error');
                    if(document.getElementById("email-error").style.display != "block"){
                        document.getElementById("email-error").style.display = "block";
                    }
                }else{
                    document.getElementById("email-box").classList.remove('input-box-error');
                    document.getElementById("email-box").classList.add('input-box');
                    if(document.getElementById("email-error").style.display != "none"){
                        document.getElementById("email-error").style.display = "none";                            
                    }
                }
            }

            function checkPhone(phone){
                let isnum = /^\d+$/;
                if(!phone.match(isnum) || phone.length < 9 || phone.length > 13){
                    document.getElementById("phone-box").classList.remove('input-box');
                    document.getElementById("phone-box").classList.add('input-box-error');
                    if(document.getElementById("phone-error").style.display != "block"){
                        document.getElementById("phone-error").style.display = "block";
                    }
                }else{
                    document.getElementById("phone-box").classList.remove('input-box-error');
                    document.getElementById("phone-box").classList.add('input-box');
                    if(document.getElementById("phone-error").style.display != "none"){
                        document.getElementById("phone-error").style.display = "none";                            
                    }
                }
            }

            $("#form").submit(function(){
                password = $('#password').val();
                retype = $('#password_retype').val();

                if(password != retype){
                    // document.getElementById("checkpassword").style.display = 'block';
                    toastr.error("Confirm password failed!", 'Gagal!')
                    $('#password').val("");
                    $('#password_retype').val("");
                    event.preventDefault();
                }

                month = $('#birthdate_month').val();
                date = $('#birthdate_date').val();
                year = $('#birthdate_year').val();

                if(month == null || year == null || date == null){
                    // document.getElementById("checkpassword").style.display = 'block';
                    toastr.error("Date of Birth required!", 'Gagal!')
                    $('#birthdate_month').val("#").change();
                    $('#birthdate_date').val("#").change();
                    $('#birthdate_year').val("#").change();
                    event.preventDefault();
                }
                
                document.getElementById("form").submit();
            });
        </script>
    @endsection
    @include('dashboard.layout.js')
</body>

</html>
