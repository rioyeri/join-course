<!-- ======= About Us Section ======= -->
<section id="about" class="about">
    <div class="container">
        <div class="section-header">
            <h2 data-aos="zoom-in-down">{{ $content[6]->title }}</h2>
            @isset($content[6]->subtitle)
                <p data-aos="zoom-out-up" data-aos-delay="250">{{ $content[6]->subtitle }}</p>
            @endisset
        </div>

        <div class="row gy-4">
            <div class="col-lg-6" data-aos="zoom-in">
                @foreach ($content[6]->detail as $detail)
                    @isset($detail->title)
                        <h3 data-aos="zoom-in-down">{{ $detail->title }}</h3>
                    @endisset
                    <p data-aos="zoom-in-up" data-aos-delay="100">{{ $detail->description }}</p>
                @endforeach
                <p data-aos="zoom-out-up" data-aos-delay="250">{{ $content[9]->title }}</p>
                <div class="content-benefit">
                    <ul>
                        @php($i=1)
                        @foreach($content[9]->detail as $detail)
                        <li data-aos="zoom-in-right" data-aos-delay="{{ $i * 100 }}"><i class="bi bi-{{ $i }}-circle"></i> <strong>{{ $detail->title }}</strong>
                            <p>{{ $detail->description }}</p>
                        </li>
                        @php($i++)
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="content ps-0 ps-lg-5">
                    <h3 data-aos="zoom-in-down" data-aos-delay="100">{{ $content[7]->title }}</h3>
                    <ul>
                        @php($i=1)
                        @foreach($content[7]->detail as $detail)
                        <li data-aos="zoom-in-right" data-aos-delay="{{ $i * 100 }}"><i class="bi bi-{{ $i }}-circle"></i> <strong>{{ $detail->title }}</strong>
                            <p>{{ $detail->description }}</p>
                        </li>
                        @php($i++)
                        @endforeach
                    </ul>
                    <img data-aos="zoom-in-down" data-aos-delay="100" src="{{ asset('landingpage/assets/img/'.$content[6]->detail[0]->image) }}" class="img-fluid rounded-4 mb-4" alt="">
                </div>
            </div>
        </div>
    </div>
</section><!-- End About Us Section -->
