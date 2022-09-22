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
        <a href="index.html" class="logo d-flex align-items-center">
            <!-- Uncomment the line below if you also wish to use an image logo -->
            <!-- <img src="assets/img/logo.png" alt=""> -->
            <h1>Impact<span>.</span></h1>
        </a>
        <nav id="navbar" class="navbar">
            <ul>
                <li><a href="#hero">Home</a></li>
                <li><a href="#about">About</a></li>
                {{-- <li><a href="#services">Services</a></li>
                <li><a href="#portfolio">Portfolio</a></li> --}}
                <li><a href="#team">Team</a></li>
                <li><a href="blog.html">Blog</a></li>
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
                        {{-- <li>
                            <p class="green">You have 4 pending tasks</p>
                        </li> 
                        <li>
                            <a href="index.html#">
                                <div class="task-info">
                                    <div class="desc">Dashio Admin Panel</div>
                                    <div class="percent">40%</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="40"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                        <span class="sr-only">40% Complete (success)</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="index.html#">
                                <div class="task-info">
                                    <div class="desc">Database Update</div>
                                    <div class="percent">60%</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="60"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                        <span class="sr-only">60% Complete (warning)</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="index.html#">
                                <div class="task-info">
                                    <div class="desc">Product Development</div>
                                    <div class="percent">80%</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="80"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 80%">
                                        <span class="sr-only">80% Complete</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="index.html#">
                                <div class="task-info">
                                    <div class="desc">Payments Sent</div>
                                    <div class="percent">70%</div>
                                </div>
                                <div class="progress progress-striped">
                                    <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="70"
                                        aria-valuemin="0" aria-valuemax="100" style="width: 70%">
                                        <span class="sr-only">70% Complete (Important)</span>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li class="external">
                            <a href="#">See All Tasks</a>
                        </li> --}}
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