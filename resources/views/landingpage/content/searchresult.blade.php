@extends('landingpage.layout.page')

@section('content')
<!-- ======= Our Team Section ======= -->
<section id="team" class="team">
    <div class="container" data-aos="fade-up">

        <div class="section-header" style="margin-top: 40px;">
            <h2>Hasil Pencarian : <span style="font-style: italic"><strong>"{{ $keyword }}"</strong></span></h2>
        </div>

        @if (!$results)
            <p style="font-style: italic; font-size: 20px;text-align: center; padding-bottom: 60px;">Guru tidak ditemukan dari kata kunci yang anda berikan</p>
        @else
            <div class="row gy-4">
                @php($i=1)
                @foreach ($results as $result)
                    <div class="col-xl-3 col-md-6 d-flex" data-aos="fade-up" data-aos-delay="@php(100 * $i)">
                        <div class="member">
                            <img src="{{ $result->image }}" class="img-fluid" style="object-fit:cover; min-height: 250px; min-width: 250px; max-height:250px;" alt="">
                            <h4>{{ $result->title }}</h4>
                            <span>{{ $result->subtitle }}</span>
                            <p>{{ $result->description }}... <a href="javascript:;" data-toggle="modal" data-target="#exampleModalCenter" onclick="showDetail({{ $result->id }})">lihat lebih banyak</a></p>
                        </div>
                    </div><!-- End Team Member -->
                    @php($i++)
                @endforeach
            </div>
        @endif
    </div>
</section><!-- End Our Team Section -->
@endsection