<!-- ======= Our Services Section ======= -->
<section id="services" class="services sections-bg">
    <div class="container">
        <div class="section-header">
            <h2 data-aos="zoom-in-down" data-aos-offset="250" data-aos-duration="500">{{ $content[1]->title }}</h2>
            <p data-aos="zoom-out-up" data-aos-offset="250" data-aos-duration="500" data-aos-delay="250">{{ $content[1]->subtitle }}</p>
        </div>
        <div class="row gy-4">
            @php($i=1)
            @foreach($content[1]->detail as $key => $detail)
                <div class="@if($key == 4 && count($content[1]->detail) == 5) col-lg-8 @else {{ $content[1]->column_size_detail }} @endif col-md-6">
                    <div class="service-item position-relative" data-aos="flip-left" data-aos-mirror="true" data-aos-offset="200" data-aos-delay="{{ 300*$i }}" data-aos-duration="1000">
                        <div class="icon">
                            <i class="{{ $detail->image }}"></i>
                        </div>
                        <h3>{{ $detail->title }}</h3>
                        <p>{{ $detail->description }}</p>
                        @if($detail->link_text != NULL)
                            <a href="{{ $detail->link }}" class="readmore stretched-link">{{ $detail->link_text }} <i class="bi bi-arrow-right"></i></a>
                        @endif
                    </div>
                </div><!-- End Service Item -->
                @php($i++)
            @endforeach
        </div>
    </div>
</section><!-- End Our Services Section -->