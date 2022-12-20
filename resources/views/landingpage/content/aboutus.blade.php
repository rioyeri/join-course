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
                    <h3 data-aos="zoom-in-down">{{ $detail->title }}</h3>
                    <img data-aos="zoom-in-down" data-aos-delay="100" src="{{ asset('landingpage/assets/img/'.$detail->image) }}" class="img-fluid rounded-4 mb-4" alt="">
                    <p data-aos="zoom-in-up" data-aos-delay="100">{{ $detail->description }}</p>
                @endforeach
            </div>
            <div class="col-lg-6">
                <div class="content ps-0 ps-lg-5">
                    <h3 data-aos="zoom-in-down" data-aos-delay="100">{{ $content[7]->title }}</h3>
                    {{-- <p class="fst-italic">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore
                        magna aliqua.
                    </p> --}}
                    <ul>
                        @php($i=1)
                        @foreach($content[7]->detail as $detail)
                        <li data-aos="zoom-in-right" data-aos-delay="{{ $i * 100 }}"><i class="bi bi-{{ $i }}-circle"></i> <strong>{{ $detail->title }}</strong>
                            <p>{{ $detail->description }}</p>
                        </li>
                        @php($i++)
                        @endforeach
                    </ul>
                    {{-- <div class="position-relative mt-4">
                        <img src="{{ asset('landingpage/assets/img/about-2.jpg') }}" class="img-fluid rounded-4"
                            alt="">
                        <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox play-btn"></a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</section><!-- End About Us Section -->
