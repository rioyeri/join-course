<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ asset('dashboard/lib/jquery/jquery.min.js') }}"></script>

<!-- Vendor JS Files -->
<script src="{{ asset('landingpage/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('landingpage/assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('landingpage/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
<script src="{{ asset('landingpage/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
<script src="{{ asset('landingpage/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('landingpage/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
<script src="{{ asset('landingpage/assets/vendor/php-email-form/validate.js') }}"></script>
<!-- Toastr js -->
<script src="{{ asset('dashboard/additionalplugins/toastr/toastr.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('dashboard/additionalplugins/select2/js/select2.min.js') }}" type="text/javascript"></script>
@yield('js')

<!-- Template Main JS File -->
<script src="{{ asset('landingpage/assets/js/main.js') }}"></script>

@include('landingpage.layout.script-js')

@if (session('status'))
    <script>
        var status = "{{session('status')}}";
        // Display a success toast, with a title
        toastr.success(status, 'Success')
    </script>
@elseif(session('warning'))
    <script>
        var status = "{{session('warning')}}";
        // Display a success toast, with a title
        toastr.warning(status, 'Warning!')
    </script>
@elseif(session('failed'))
<script>
    var status = "{{session('failed')}}";
    // Display a success toast, with a title
    toastr.error(status, 'Login Gagal')
</script>
@endif
@if ($errors->any())
    @php
        $er="";
    @endphp
    @foreach ($errors->all() as $error)
        @php
        $er .= "<li>".$error."</li>";
        @endphp
    @endforeach
    <script>
        var error = "<?=$er?>";
        // Display an error toast, with a title
        toastr.error(error, 'Error!!!')
    </script>
@endif

@yield('script-js')