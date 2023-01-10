<!--header start-->
<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
    </div>
    <!--logo start-->
    <a href="{{ route('getHome') }}" class="logo"><b>Flash <span>Academia</span></b></a>
    <!--logo end-->
    @if(session()->has('user_id'))
    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <li><a class="logout" href="{{ route('Logout') }}">Logout</a></li>
        </ul>
    </div>
    @endif
</header>
<!--header end-->