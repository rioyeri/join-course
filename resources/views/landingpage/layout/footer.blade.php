@php
    use App\Models\User;

    $phone_format = User::getFormatWANumber($company_profile[4]->content);
    $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format;
@endphp
<!-- ======= Footer ======= -->
<footer id="footer" class="footer">
    <div class="container">
        <div class="row gy-4">
            <div class="col-lg-5 col-md-12 footer-info">
                <a href="{{ route('getHome') }}" class="logo d-flex align-items-center">
                    {{-- <span>{{ $company_profile[0]->content }}</span> --}}
                    Flash<span>Academia</span>
                </a>
                <div class="social-links d-flex mt-4">
                    @for($i=5; $i<9; $i++)
                        @if($company_profile[$i]->status == 1)
                            <a href="{{ $company_profile[$i]->content }}" class="{{ $company_profile[$i]->title }}" target="_blank"><i class="bi bi-{{ $company_profile[$i]->title }}"></i></a>
                        @endif
                    @endfor
                </div>
            </div>

            <div class="col-lg-2 col-6 footer-links">
                {{-- <h4>Tentang</h4>
                <ul>
                    <li><a href="#">Siapa kami?</a></li>
                    <li><a href="#">Guru-guru kami</a></li>
                    <li><a href="#">Syarat dan ketentuan</a></li>
                    <li><a href="#">Kebijakan privasi</a></li>
                </ul> --}}
            </div>

            <div class="col-lg-2 col-6 footer-links">
                <br><br>
                <ul>
                    {{-- <li><a href="#">Subjek yang diajarkan</a></li>
                    <li><a href="#">Kursus bersama kami</a></li>
                    <li><a href="#">Bergabung menjadi guru</a></li> --}}
                </ul>
            </div>

            <div class="col-lg-3 col-md-12 footer-contact text-center text-md-start">
                <h4>Kontak Kami</h4>
                <p>
                    @if($company_profile[1]->status == 1)
                        {{$company_profile[1]->content}}
                    @endif
                    <br>
                    @if($company_profile[2]->status == 1)
                        <strong>Telepon:</strong> {{ $company_profile[2]->content }}<br>
                    @endif
                    @if($company_profile[3]->status == 1)
                        <strong>Email:</strong> {{ $company_profile[3]->content }}<br>
                    @endif
                    @if($company_profile[4]->status == 1)
                        <strong>Admin Whatsapp:</strong> <a href="{{ $phone_redirect }}" class="btn-wa-admin" target="_blank" title="Whatsapp Admin"><ins><i class="fa fa-whatsapp"></i> {{ $company_profile[4]->content }}</ins></a><br>
                    @endif
                </p>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="copyright">
            &copy; 2022 <strong><span>FlashAcademia</span></strong>
        </div>
        <div class="credits">
            <!-- All the links in the footer should remain intact. -->
            <!-- You can delete the links only if you purchased the pro version. -->
            <!-- Licensing information: https://bootstrapmade.com/license/ -->
            <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/impact-bootstrap-business-website-template/ -->
            {{-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> --}}
        </div>
    </div>
</footer><!-- End Footer -->
<!-- End Footer -->