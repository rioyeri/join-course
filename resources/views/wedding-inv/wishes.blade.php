<style>
    td {
        font-size: 0.8em;
    }
</style>
<!-- ======= Contact Section ======= -->
<section id="contact" class="padd-section">

    <div class="container" data-aos="fade-up">

        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">

            <div class="col-lg-4 col-md-4" style="background: #f8f7f4;">
                <div class="section-title text-center" style="margin: 20px">
                    <h2>Ucapan</h2>
                    <p class="separator">Tulis ucapan dan doamu kepada kami</p>
                </div>
                <div class="form">
                    <form action="{{ route('sendMessageInvitation', ['invitation_id'=>$invitation->invitation_id]) }}" method="post" role="form" class="php-email-form">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" id="name" placeholder="Nama Anda">
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" name="message" id="message" rows="3" placeholder="Tulis Ucapan"></textarea>
                            <span class="help-block"><small>* Pastikan nama dan ucapan diisi dengan benar. Karena tidak dapat dibatalkan.</small></span>
                        </div>
                        <div class="my-3">
                            <div class="loading">Memuat</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Pesanmu tersimpan. Terima Kasih!</div>
                        </div>
                        <div class="text-center"><button type="submit">Kirim Ucapan</button></div>
                    </form>
                </div>
            </div>

            @if(count($messages) != 0)
            <div class="col-lg-1"></div>

            <div class="col-lg-7 col-md-8" style="background: #f8f7f4;">
                <div class="section-title text-center" style="margin: 20px">
                    <h2>Dari Orang Terkasih</h2>
                </div>
                <div class="form-group mt-3" style="margin:20px;">
                    {{-- <textarea class="form-control" name="message" rows="10" style="resize: auto; overflow: auto;" readonly>
                        @foreach ($messages as $message)- <b>{{ $message->sender_name }}</b> : {{ $message->sender_message }} <br> @endforeach
                    </textarea> --}}
                    <table id="responsive-datatable" class="table display dt-responsive wrap" cellspacing="0" width="100%">
                        <tbody>
                            @foreach($messages as $message)
                            <tr>
                                <td width="5%"><i class="fa fa-heart"></i></td>
                                <td width="20%"><strong>{{ $message->sender_name }}</strong></td>
                                <td width="5%">:</td>
                                <td width="70%">{{ $message->sender_message }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</section><!-- End Contact Section -->