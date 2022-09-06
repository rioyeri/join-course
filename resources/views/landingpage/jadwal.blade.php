<!-- ======= Our Services Section ======= -->
    <section id="services" class="services section-bg">
      <div class="container">

        <div class="section-title">
          <h2>Jadwal Ibadah</h2>
          {{-- <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p> --}}
        </div>

        <div class="row">
            <div class="owl-carousel">
                @foreach($jadwals as $jadwal)
                    @php
                        $daynote = null;
                        if($jadwal->day_note != null){
                            $daynote = " (".$jadwal->day_note.")";
                        }
                    @endphp
                    <div class="col-md-4 col-lg-11 align-items-stretch mb-5 mb-lg-0">
                        <div class="icon-box">
                            {{-- <div class="icon"><i class="bx bxl-dribbble"></i></div> --}}
                            <div class="icon"><img src="{{ asset('assets/images/jadwal/'.$jadwal->icon) }}" class="img-fluid" alt=""></div>
                            <h4 class="title" style="text-align: center;"><a href="">{{ $jadwal->name }}</a></h4>
                            {{-- <p class="description">{{ $jadwal->notes }}</p> --}}
                            <p class="description" style="text-align: center;">{{ $jadwal->namahari->day_name }}{{ $daynote }}</p>
                            <p class="description" style="text-align: center;">{{ $jadwal->start_time }}-{{ $jadwal->end_time }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

      </div>
    </section><!-- End Our Services Section -->
