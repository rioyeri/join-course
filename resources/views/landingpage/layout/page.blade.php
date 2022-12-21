<!DOCTYPE html>
<html>
@include('landingpage.layout.head')
<body>
    @php
        use App\Models\User;
        $phone_format = User::getFormatWANumber($company_profile[4]->content);
        $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format;
    @endphp
    @include('landingpage.layout.navigation')
    @yield('content')
    @include('landingpage.layout.footer')

    <a href="{{ $phone_redirect }}" class="wa-contact d-flex align-items-center justify-content-center" target="_blank"><i class="bi bi-whatsapp"></i>Chat Admin</a>
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>
    @include('landingpage.layout.js')
</body>

</html>
