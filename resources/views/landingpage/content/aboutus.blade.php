<!-- ======= About Us Section ======= -->
<section id="about" class="about">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <h2>{{ $content[6]->title }}</h2>
            @isset($content[6]->subtitle)
            <p>{{ $content[6]->subtitle }}</p>
            @endisset
        </div>

        <div class="row gy-4">
            <div class="col-lg-6">
                @foreach ($content[6]->detail as $detail)
                    <h3>{{ $detail->title }}</h3>
                    <img src="{{ asset('landingpage/assets/img/'.$detail->image) }}" class="img-fluid rounded-4 mb-4" alt="">
                    <p>{{ $detail->description }}</p>
                @endforeach
            </div>
            <div class="col-lg-6">
                <div class="content ps-0 ps-lg-5">
                    <h3>{{ $content[7]->title }}</h3>
                    {{-- <p class="fst-italic">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore
                        magna aliqua.
                    </p> --}}
                    <ul>
                        @php($i=1)
                        @foreach($content[7]->detail as $detail)
                        <li><i class="bi bi-{{ $i }}-circle"></i> <strong>{{ $detail->title }}</strong>
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
