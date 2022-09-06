<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">
    <div class="container d-flex align-items-center justify-content-between">

    <div id="logo">
        @if($invitation->format == 1)
            <h1><a href="#hero"><span>{{ $invitation->groom_nickname }}</span>&<span>{{ $invitation->bride_nickname }}</span></a></h1>
        @else
            <h1><a href="#hero"><span>{{ $invitation->bride_nickname }}</span>&<span>{{ $invitation->groom_nickname }}</span></a></h1>
        @endif
        <!-- Uncomment below if you prefer to use an image logo -->
        <!-- <a href="index.html"><img src="assets/img/logo.png" alt="" title="" /></a>-->
    </div>

    <nav id="navbar" class="navbar">
        <ul>
        <li><a class="nav-link scrollto active" href="#hero">Beranda</a></li>
        <li><a class="nav-link scrollto" href="#about-us">Tentang Kami</a></li>
        <li><a class="nav-link scrollto" href="#get-started">Undangan</a></li>
        @if (count($galleries) != 0)
            <li><a class="nav-link scrollto" href="#screenshots">Galeri</a></li>
        @endif
        <li><a class="nav-link scrollto" href="#features">Hadiah Pernikahan</a></li>
        <li><a class="nav-link scrollto" href="#contact">Ucapan</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
    </nav><!-- .navbar -->

    </div>
</header><!-- End Header -->