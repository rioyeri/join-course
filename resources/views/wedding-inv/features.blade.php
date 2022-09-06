<!-- ======= Features Section ======= -->
<section id="features" class="padd-section text-center">

    <div class="container" data-aos="fade-up">
      <div class="section-title text-center">
        <h2>Hadiah Pernikahan</h2>
        <p class="separator">Untuk Keluarga dan Teman yang ingin memberikan hadiah berupa Uang, Kami mengucapkan Terima Kasih dan dengan senang hati menerimanya. Dapat ditransfer melalui :</p>
      </div>

      <div class="row" data-aos="fade-up" data-aos-delay="100">

        @foreach ($giftbox as $gift)
          <div class="@if(count($giftbox)==2) col-md-6 col-lg-6 @elseif(count($giftbox)==3) col-md-4 col-lg-4 @else col-md-12 col-lg-12 @endif">
            <div class="feature-block">
              <h6>{{ $gift->account_type }}</h6>
              <h6>a.n. {{ $gift->account_name }}</h6>
              <h4 style="margin-top: 20px;">{{ $gift->account_number }}</h4>
              <a href="javascript:;" id="norek{{ $gift->account_number }}" data-toggle="popover" class="btn-rounded" onclick="copyNumber({{ $gift->account_number }})">Salin Nomor Rekening</a>
            </div>
          </div>
        @endforeach

        {{-- <div class="col-md-6 col-lg-6">
          <div class="feature-block">
            <h6>BRI</h6>
            <h6>a.n. Putra Wahyu Pamekas</h6>
            <h4 style="margin-top: 20px;">658301023016539</h4>
            <a href="javascript:;" id="norekputra" data-toggle="popover" class="btn-rounded" onclick="copyNumber(658301023016539)">Salin Nomor Rekening</a>
          </div>
        </div>

        <div class="col-md-6 col-lg-6">
          <div class="feature-block">
            <h6>BTPN</h6>
            <h6>a.n. Lois Aprilia Irianti</h6>
            <h4 style="margin-top: 20px;">90270167617</h4>
            <a href="javascript:;" id="noreklois" data-toggle="popover" class="btn-rounded" onclick="copyNumber(90270167617)">Salin Nomor Rekening</a>
          </div>
        </div> --}}

      </div>
      @if($invitation->invitation_id == "yizharlois")
      <div class="row" data-aos="fade-up" data-aos-delay="110">
        <p>* Harap simpan <strong>Bukti Transfer</strong> dan tunjukan ketika hadir di Resepsi kepada Penerima Tamu, untuk mendapatkan souvenir pernikahan dari kami ya :)</p>
      </div>
      @endif
    </div>
  </section><!-- End Features Section -->