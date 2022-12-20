<!-- ======= Our Team Section ======= -->
<section id="team" class="team sections-bg">    
    <div class="container">
        <div class="section-header">
            <h2 data-aos="zoom-in-down">{{ $content[2]->title }}</h2>
            <p data-aos="zoom-out-up" data-aos-delay="250">{{ $content[2]->subtitle }}</p>
        </div>
        <div class="row gy-4">
            @php($i=1)
            @foreach ($content[2]->detail as $teacher)
                <div class="col-xl-3 col-md-6 d-flex" data-aos="zoom-in-up" data-aos-delay="{{ 200 * $i }}">
                    <div class="member">
                        <img src="{{ $teacher->image }}" class="img-fluid" style="object-fit:cover; min-height: 350px; min-width: 250px; max-height:250px;" alt="">
                        <h4>{{ $teacher->title }}</h4>
                        @if($teacher->link_text != "")
                            <p><i class="bi bi-pin-map"></i> {{ $teacher->link_text }}</p>
                        @endif
                        <span>{{ $teacher->subtitle }}</span>
                        <p>{{ $teacher->description }}... <a href="javascript:;" data-toggle="modal" data-target="#exampleModalCenter" onclick="showDetail({{ $teacher->link }})">lihat lebih banyak</a></p>
                    </div>
                </div><!-- End Team Member -->
                @php($i++)
            @endforeach
        </div>
        <div class="text-center" style="margin: 40px 0 0 0">
            <a href="{{ route('showAllTeacher') }}">Tampilkan semua guru <i class="bi bi-caret-down-fill"></i></a>
        </div>
    </div>
</section><!-- End Our Team Section -->
