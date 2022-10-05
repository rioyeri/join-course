<style>
#top_menu .nav > li, ul.top-menu > li {
    float: left;
}

.notify-row {
    float: left;
    margin-top: 15px;
    margin-left: 92px;
}

.notify-row .notification span.label {
    display: inline-block;
    height: 18px;
    width: 20px;
    padding: 5px;
}

ul.top-menu > li > a {
    color: #666666;
    font-size: 16px;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    border:1px solid #666666 !important;
    padding: 2px 6px;
    margin-right: 15px;
}

ul.top-menu > li > a:hover, ul.top-menu > li > a:focus {
    border:1px solid #b6b6b6 !important;
    background-color: transparent !important;
    border-color: #b6b6b6 !important;
    text-decoration: none;
    border-radius: 4px;
    -webkit-border-radius: 4px;
    color: #b6b6b6 !important;
}

.notify-row .badge {
    position: absolute;
    right: -10px;
    top: -10px;
    z-index: 100;
}
.dropdown-menu.extended {
    max-width: 300px !important;
    min-width: 160px !important;
    top: 42px;
    /* width: 235px; */
    width: 270px;
    padding: 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.176) !important;
    border: none !important;
    border-radius: 4px;
    -webkit-border-radius: 4px;
}

@media screen and (-webkit-min-device-pixel-ratio:0) {
    /* Safari and Chrome */
    .dropdown-menu.extended  {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.176) !important;
    };
}

.dropdown-menu.extended li p {
    background-color: #F1F2F7;
    color: #666666;
    margin: 0;
    padding: 10px;
    border-radius: 4px 4px 0px 0px;
    -webkit-border-radius: 4px 4px 0px 0px;
}

.dropdown-menu.extended li p.green {
    background-color: #4ECDC4;
    color: #fff;
}

.dropdown-menu.extended li a {
    border-bottom: 1px solid #EBEBEB !important;
    font-size: 12px;
    list-style: none;
}

.dropdown-menu.extended li a {
    padding: 15px 10px !important;
    width: 100%;
    display: inline-block;
}

.dropdown-menu.extended li a:hover {
    background-color: #F7F8F9 !important;
    color: #2E2E2E;
}
</style>

<header id="header" class="header d-flex align-items-center">
    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">
        <a href="{{ route('getHome') }}" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>Flash<span>Academia</span></h1>
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="#hero">Beranda</a></li>
                <li><a href="#services">Tentang Kami</a></li>
                {{-- <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li> --}}
                <li><a href="#team">Guru-guru</a></li>
                {{-- <li><a href="blog.html">Blog</a></li> --}}
                {{-- <li class="dropdown"><a href="#"><span>Drop Down</span> <i
                            class="bi bi-chevron-down dropdown-indicator"></i></a>
                    <ul>
                        <li><a href="#">Drop Down 1</a></li>
                        <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i
                                    class="bi bi-chevron-down dropdown-indicator"></i></a>
                            <ul>
                                <li><a href="#">Deep Drop Down 1</a></li>
                                <li><a href="#">Deep Drop Down 2</a></li>
                                <li><a href="#">Deep Drop Down 3</a></li>
                                <li><a href="#">Deep Drop Down 4</a></li>
                                <li><a href="#">Deep Drop Down 5</a></li>
                            </ul>
                        </li>
                        <li><a href="#">Drop Down 2</a></li>
                        <li><a href="#">Drop Down 3</a></li>
                        <li><a href="#">Drop Down 4</a></li>
                    </ul>
                </li> --}}
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" id="login" onclick="showLoginForm()">
                        Login
                    </a>
                    <ul class="dropdown-menu extended" id="loginForm">
                        <form id="login-form" class="form-horizontal m-t-20" action="{{route('Login')}}" method="POST">
                            @csrf
                            <li>
                                <div class="m-3">
                                    <input class="form-control" type="text" name="login_id" id="login_id" placeholder="Username / Email">
                                </div>
                            </li>
                            <li>
                                <div class="m-3">
                                <input class="form-control" type="password" name="password" id="password" placeholder="Password">
                                </div>
                            </li>
                            <div class="form-group text-center m-t-30">
                                <div class="col-xs-12">
                                    <button class="btn btn-login waves-effect" id="button-login" type="submit">Login</button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="text-center register-group">
                                <p class="text-muted">Belum mempunyai akun? <a href="{{ route('get_register') }}" class="btn-register" style="display:inline; margin-left:-20px">Daftar</a></p>
                                <p class="text-muted">Atau</p>
                                <a href="{{ route('google.redirect') }}" class="btn-google-login" id="button-google"><i class="fa fa-google-plus" style="margin-left:25px"></i>Login dengan Google</a>
                            </div>

                        </div>
                    </ul>
                </li>
                <!-- settings end -->
            </ul>
        </nav><!-- .navbar -->
        <i class="mobile-nav-toggle mobile-nav-show bi bi-list"></i>
        <i class="mobile-nav-toggle mobile-nav-hide d-none bi bi-x"></i>
    </div>
</header><!-- End Header -->
<!-- End Header -->