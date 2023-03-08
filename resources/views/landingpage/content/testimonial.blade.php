<!-- ======= Testimonials Section ======= -->
<section id="testimonials" class="testimonials sections-bg">
    <div class="container">
        <div class="section-header">
            <h2 data-aos="zoom-in-down">{{ $content[3]->title }}</h2>
            <p data-aos="zoom-out-up" data-aos-delay="250">{{ $content[3]->subtitle }}</p>
        </div>
        <div class="slides-3 swiper" data-aos="flip-down" data-aos-delay="250" data-aos-anchor-placement="center-bottom">
            <div class="swiper-wrapper">
                @foreach ($content[3]->detail as $detail)
                    <div class="swiper-slide">
                        <div class="testimonial-wrap">
                            <div class="testimonial-item">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $detail->image }}" class="testimonial-img flex-shrink-0" alt="">
                                    <div>
                                        <h3>{{ $detail->title }}</h3>
                                        <h4>{{ $detail->subtitle }}</h4>
                                    </div>
                                </div>
                                <p>
                                    <i class="bi bi-quote quote-icon-left"></i>
                                    {{ $detail->description }}
                                    <i class="bi bi-quote quote-icon-right"></i>
                                </p>
                            </div>
                        </div>
                    </div><!-- End testimonial item -->                    
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section><!-- End Testimonials Section -->
