<!-- ======= Hero Section ======= -->
<section id="hero" class="bg" style="background-image: url({{ asset('multimedia/'.$invitation->invitation_id.'/'.$complement->banner) }})">
  <div class="hero-container" data-aos="fade-in">
    {{-- <div class="carousel-background"><img src="{{ asset('assets/images/banner/banner radio.jpg') }}" alt=""></div> --}}
    <h2>Pernikahan</h2>
    <h1 data-aos="zoom-out" data-aos-delay="100">
      @if($invitation->format == 1)
        <strong>{{ $invitation->groom_nickname }}</strong> <span>&</span> <strong>{{ $invitation->bride_nickname }}</strong></h1>
      @else
        <strong>{{ $invitation->bride_nickname }}</strong> <span>&</span> <strong>{{ $invitation->groom_nickname }}</strong></h1>
      @endif
    <h4 style="font-size:15px"><strong>{{ $events->event_detail[0]->tanggal }}</strong></h4>
    <h5>Teruntuk</h5>
    <h3><strong>{{ $nama }}</strong></h3>
    <h4>di {{ $posisi }}</h4>
    {{-- <img src="assets_eStartup/img/hero-img.png" alt="Hero Imgs" data-aos="zoom-out" data-aos-delay="100"> --}}
    <a href="#about-us" class="btn-get-started scrollto" onClick="music();">Pemberitahuan</a>
  </div>
</section><!-- End Hero Section -->