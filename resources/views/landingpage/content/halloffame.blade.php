<!-- ======= Hall of fame Section ======= -->
<section id="halloffames" class="halloffames">
    <div class="container">
        <div class="section-header">
            <h2 data-aos="zoom-in-down">{{ $content[8]->title }}</h2>
            <p data-aos="zoom-out-up" data-aos-delay="250">{{ $content[8]->subtitle }}</p>
        </div>
        <div class="slides-3 swiper" data-aos="flip-up" data-aos-delay="250" data-aos-anchor-placement="center-bottom">
            <div class="swiper-wrapper">
                @foreach ($content[8]->detail as $detail)
                    <div class="swiper-slide">
                        <div class="halloffame-wrap">
                            <div class="halloffame-item">
                                <div class="d-flex align-items-center">
                                    <a href="{{ $detail->image }}" class="image-popup">
                                    <img src="{{ $detail->image }}" class="halloffame-img" alt="{{ asset('dashboard/assets/noimage.jpg') }}">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- End testimonial item -->                    
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section><!-- End Hall of fame Section -->
