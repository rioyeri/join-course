<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Nada Sederhana</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/logo_mempelai.ico') }}">

        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

        <!-- Notification css (Toastr) -->
        <link href="{{ asset('assets/plugins/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />

        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>

    </head>

    <style>
        .logo h1 {
            font-size: 28px;
            margin: 0;
            padding: 10px 0;
            line-height: 1;
            font-weight: 400;
        }

        .logo h1 a, #header .logo h1 a:hover {
            color: #425451;
            text-decoration: none;
            font-family: "Poppins", sans-serif;
            font-weight: 600;
        }

        #register button:hover {
            color: #425451;
            background: #ffffff;
        }

        .btn-regist {
            background: #425451;
            color: #ffffff;
        }
    </style>

    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class="text-center">
                <div class="logo">
                    <h1 class="text-light"><a href="{{ route('getHome') }}"><span>Nada Sederhana</span></a></h1>
                </div>
                {{-- <a href="{{ route('getHome') }}" class="logo"><span><span><br>Gereja Pantekosta Tabernakel<br></span>Tulungagung</span></a> --}}
                {{-- <h5 class="text-muted mt-0 font-600">Sign In to Editor Page</h5> --}}
            </div>
        	<div class="m-t-40 card-box">
                <div class="text-center">
                    <h4 class="text-uppercase font-bold mb-0">Daftar Pengguna Baru</h4>
                </div>
                <div class="p-20">
                    <form class="form-horizontal m-t-20" id="form" action="{{route('post_register')}}" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required placeholder="Nama Lengkap" name="name" id="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="text" required placeholder="Username" name="username" id="username" onchange="checkUniqueUsername()">
                                <span class="help-block text-danger" id="usernamewarningspan" style="display:none;"><small>Username sudah pernah terdaftar. Gunakan username lain</small></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required placeholder="Password" name="password" id="password" autocomplete="new-password" onchange="checkPassword()">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-12">
                                <input class="form-control" type="password" required="" placeholder="Masukan Password sekali lagi" name="passwordcheck" id="passwordcheck" onchange="checkPassword()">
                                <span class="help-block text-danger" style="display:none" id="passwordwarningspan"><small>Password tidak sama</small></span>
                            </div>
                        </div>
                        <input type="hidden" id="statusform" value="0">

                        <div class="form-group text-center m-t-30">
                            <div class="col-xs-12">
                                <button class="btn btn-block waves-effect btn-secondary disabled" id="registButton" type="submit">Daftarkan</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!-- end card-box-->
            <div class="row">
                <div class="col-sm-12 text-center">
                    <p class="text-muted">Sudah memiliki akun? <a href="{{ route('getHome2') }}" class="text-primary m-l-5"><b>Login</b></a></p>
                </div>
            </div>
        </div>
        <!-- end wrapper page -->
        @include('layout.js')
        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/popper.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>

        <!-- Toastr js -->
        {{-- <script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script> --}}

        <!-- App js -->
        <script src="{{ asset('assets/js/jquery.core.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.app.js') }}"></script>

        <script>

            $("form").submit(function(){
                var username = $('#username').val();
                var password = $('#password').val();
                var passwordcheck = $('#passwordcheck').val();
                if(password != passwordcheck){
                    toastr.error("Password pertama dan kedua tidak sama", 'Error!!!')
                    event.preventDefault();
                }else{
                    document.getElementById("form").submit();
                }
            });

            function checkPassword(){
                var username = $('#username').val();
                var password = $('#password').val();
                var passwordcheck = $('#passwordcheck').val();

                if(username != "" && password != "" && passwordcheck != ""){
                    if(password != passwordcheck && (password != "" && passwordcheck != "")){
                        document.getElementById("passwordwarningspan").style.display = 'block';
                    }else{
                        document.getElementById("passwordwarningspan").style.display = 'none';
                        document.getElementById("registButton").classList.remove('disabled');
                        document.getElementById("registButton").classList.add('btn-regist');
                        document.getElementById("registButton").classList.remove('btn-secondary');
                    }
                }else{
                    if(password != passwordcheck && (password != "" && passwordcheck != "")){
                        document.getElementById("passwordwarningspan").style.display = 'block';
                    }
                    console.log(username, password, passwordcheck);

                    document.getElementById("registButton").classList.add('disabled');
                    document.getElementById("registButton").classList.remove('btn-regist');
                    document.getElementById("registButton").classList.add('btn-secondary');
                }
            }

            $(function() {
                $('#username').on('keypress', function(e) {
                    if (e.which == 32){
                        return false;
                    }
                });
            });

            function checkUniqueUsername(){
                var username = $('#username').val();
                var result="";
                $.ajax({
                    url: "{{ route('checkUsernameAvailability') }}",
                    type: "get",
                    dataType: 'json',
                    data: {
                        "username": username,
                    },success:function(data){
                        console.log(data.text);
                        if(data.text == "notavailable"){
                            document.getElementById("usernamewarningspan").style.display = 'block';
                        }else{
                            document.getElementById("usernamewarningspan").style.display = 'none';
                        }
                    }
                })
            }
        </script>

	</body>
</html>
