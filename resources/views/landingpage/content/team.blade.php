<!-- ======= Our Team Section ======= -->
<section id="team" class="team">
    {{-- <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Flash Academia</h4>
                </div>
                <div class="modal-body" id="view-form">
                </div>
            </div>
        </div>
    </div> --}}
    
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <h2>{{ $content[2]->title }}</h2>
            <p>{{ $content[2]->title }}</p>
        </div>
        <div class="row gy-4">
            @php($i=1)
            @foreach ($content[2]->detail as $teacher)
                <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="@php(100 * $i)">
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
    </div>
</section><!-- End Our Team Section -->
