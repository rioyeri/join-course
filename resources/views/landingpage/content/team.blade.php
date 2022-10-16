<!-- ======= Our Team Section ======= -->
<section id="team" class="team">
    <div class="container" data-aos="fade-up">

        <div class="section-header">
            <h2>Guru-guru kami</h2>
            <p>Kami telah bekerja sama dengan guru-guru yang profesional dan memiliki passion mengajar</p>
        </div>

        <div class="row gy-4">
            @php($i=1)
            @foreach ($content[2]->detail as $teacher)
                <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="@php(100 * $i)">
                    <div class="member">
                        <img src="{{ $teacher->image }}" class="img-fluid" style="object-fit:cover; min-height: 250px; max-height:250px;" alt="">
                        <h4>{{ $teacher->title }}</h4>
                        <span>{{ $teacher->subtitle }}</span>
                        <p>{{ $teacher->description }}... <a href="">lihat lebih banyak</a></p>
                    </div>
                </div><!-- End Team Member -->
                @php($i++)
            @endforeach
        </div>

    </div>
</section><!-- End Our Team Section -->
