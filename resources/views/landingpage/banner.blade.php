<!-- ======= Hero Section ======= -->
<section id="hero">
<div class="hero-container">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

    <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

    <div class="carousel-inner" role="listbox">
        @foreach($banners as $banner)
            @if($banner->id == 1)
                <!-- Slide 1 -->
                <div class="carousel-item @if($banner->urutan == 1) active @endif">
                    <div class="carousel-background"><img src="{{ asset('assets/images/banner/'.$banner->image) }}" alt=""></div>
                    <div class="carousel-container">
                        <div class="carousel-content">
                        <h2 class="animate__animated animate__fadeInDown">Jadwal Ibadah <span>GPT Tulungagung</span></h2>
                        <p class="animate__animated animate__fadeInUp">
                            @foreach($jadwals as $jadwal)
                                @php
                                    $daynote = null;
                                    if($jadwal->day_note != null){
                                        $daynote = " (".$jadwal->day_note.")";
                                    }
                                @endphp
                                <li><strong>{{ $jadwal->name }}</strong> - {{ $jadwal->namahari->day_name }}{{ $daynote }}, {{ $jadwal->start_time }}-{{ $jadwal->end_time }}</li>
                            @endforeach
                        </p>
                        </div>
                    </div>
                </div>
            @else
                <!-- Next Slide -->
                <div class="carousel-item @if($banner->urutan == 1) active @endif"">
                    <div class="carousel-background"><img src="{{ asset('assets/images/banner/'.$banner->image) }}" alt=""></div>
                    <div class="carousel-container">
                        <div class="carousel-content">
                            <h2 class="animate__animated animate__fadeInDown">{{ $banner->title }}</h2>
                            <p class="animate__animated animate__fadeInUp" style="white-space: pre-line">{{ $banner->description }}</p>
                            @if($banner->button == 1)
                                <a href="{{ $banner->get_link->link }}" target="_blank" class="btn-get-started animate__animated animate__fadeInUp scrollto">{{ $banner->button_name }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bi bi-chevron-double-left" aria-hidden="true"></span>
    </a>

    <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
        <span class="carousel-control-next-icon bi bi-chevron-double-right" aria-hidden="true"></span>
    </a>

    </div>
</div>
</section><!-- End Hero -->
