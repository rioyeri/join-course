<!-- ======= Get Started Section ======= -->
<section id="get-started" class="padd-section text-center">

    <div class="row">
      {{-- <div class="col-4"></div> --}}
      <div class="col-12">
        <div class="container" data-aos="fade-up">
          <div class="section-title text-center">

            <h2>{{ $events->title }}</h2>
            <p class="separator pull-center">{{ $events->description }}</p>

          </div>
        </div>
      </div>
      {{-- <div class="col-4"></div> --}}
    </div>

    <div class="container">
      <div class="row">
        @php($i=1)
        <input type="hidden" id="count_event" value="{{ count($events->event_detail) }}">
        @foreach ($events->event_detail as $event)
          <div class="@if(count($events->event_detail) == 2) col-md-6 col-lg-6 @else col-md-12 col-lg-12 @endif" data-aos="zoom-in" data-aos-delay="100*{{ $i }}">
            <div class="feature-block">
              <div class="row">
                <div class="col-5 text-center">
                  <h3>{{ $event->event_name }}</h3>
                  <input type="hidden" id=event_name" value="{{ $event->event_name }}">
                  <h6 id="countdown_hari{{ $i }}"></h6>
                  <h6 id="countdown_waktu{{ $i }}"></h6>
                </div>
                <div class="col-7">
                  <h6><input type="hidden" id="date{{ $i }}" value="{{ $event->event_date }}">{{ $event->tanggal }}</h6>
                  <h6><input type="hidden" id="hour{{ $i }}" value="{{ $event->event_time_start }}">{{ $event->waktu }}</h6>
                  <h6>di <strong>{{ $event->event_location }}</strong></h6>
                  @if($event->event_location_address != NULL)
                    <h6>{{ $event->event_location_address }}</h6>
                  @endif
                  @if ($event->event_location_url)
                    <a href="{{ $event->event_location_url }}" target="_blank" class="btn-rounded">Lihat Lokasi</a>                    
                  @endif
                  @if($event->event_streaming_channel != NULL)
                    <h6 style="margin-top: 5px">Live Streaming di Channel</h6>
                    <h6><strong>{{ $event->event_streaming_channel }}</strong></h6>
                  @endif
                  @if($event->event_streaming_link != NULL)
                    <a href="{{ $event->event_streaming_link }}" class="btn-rounded" target="_blank">Tonton Live Streaming</a>
                  @endif
                </div>
              </div>
            </div>
          </div>
          @php($i++)
        @endforeach
        {{-- <div class="col-md-6 col-lg-6" data-aos="zoom-in" data-aos-delay="100">
          <div class="feature-block">
            <div class="row">
              <div class="col-5 text-center">
                <h3>{{ $event->ceremony_name }}</h3>
                <input type="hidden" id="ceremony_name" value="{{ $event->ceremony_name }}">
                <h6 id="countdown_pemberkatan_hari"></h6>
                <h6 id="countdown_pemberkatan_waktu"></h6>
              </div>
              <div class="col-7" style="margin-top:1px;">
                <h5>{{ $event->tanggal_upacara }}</h5>
                <h5>{{ $event->waktu_upacara }}</h5>
                <h5>di <strong>{{ $event->ceremony_location }}</strong></h5>
                <h5>{{ $event->ceremony_location_address }}</h5>
                @if($event->ceremony_streaming_channel)
                <h5 style="margin-top: 5px">Live Streaming di Channel Youtube</h5>
                <h5><strong>{{ $event->ceremony_streaming_channel }}</strong></h5>
                @endif
                @if($event->ceremony_streaming_link != NULL)
                <a href="{{ $event->ceremony_streaming_link }}" class="btn-rounded" target="_blank">Tonton Live Streaming</a>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-6" data-aos="zoom-in" data-aos-delay="200">
          <div class="feature-block">
            <div class="row">
              <div class="col-5 text-center">
                <h3>{{ $event->reception_name }}</h3>
                <input type="hidden" id="reception_name" value="{{ $event->reception_name }}">
                <h6 id="countdown_resepsi_hari"></h6>
                <h6 id="countdown_resepsi_waktu"></h6>
              </div>
              <div class="col-7">
                <h6>{{ $event->tanggal_resepsi }}</h6>
                <h6>{{ $event->waktu_resepsi }}</h6>
                <h6>di <strong>{{ $event->reception_location }}</strong></h6>
                <h6>{{ $event->reception_location_address }}</h6>
                <a href="{{ $event->reception_location_url }}" target="_blank" class="btn-rounded">Lihat Lokasi</a>
              </div>
            </div>
          </div>
        </div> --}}

      </div>
    </div>

    <div class="row" style="margin-top: 50px;">
      <div class="col-2"></div>
      <div class="col-8">
        <div class="container" data-aos="fade-up">
          <div class="section-title text-center">
            <h4>Protokol Kesehatan</h4>
            <p class="separator pull-center">
              Mohon untuk anda sahabat/kerabat dan saudara untuk tetap memperhatikan protokol kesehatan yaitu Menggunakan Masker, Menjaga Jarak, Mencuci Tangan dan selalu menjaga kesehatan dan juga imun tubuh. Terima kasih
            </p>
          </div>
        </div>
      </div>
      <div class="col-2"></div>
    </div>

  </section><!-- End Get Started Section -->