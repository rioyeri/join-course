<!DOCTYPE html>
<html>
@include('landingpage.layout.head')
<body>
    @php
        use App\Models\User;

        $phone_format = User::getFormatWANumber($company_profile[4]->content);
        $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format;
    @endphp
    {{-- @include('landingpage.layout.header') --}}
    @include('landingpage.layout.navigation')
    @yield('content')
    @include('landingpage.layout.footer')
    {{-- <a href="#" class="d-flex align-items-center justify-content-center"><i class="bi bi-whatsapp"></i></a> --}}
    {{-- <div class="mute shadow px-3 py-2" style="border-radius: 20px; color: black; background-color: white;" id="mute" onClick="mute();"><i class="bi bi-whatsapp"></i></div> --}}

    <a href="{{ $phone_redirect }}" class="wa-contact d-flex align-items-center justify-content-center" target="_blank"><i class="bi bi-whatsapp"></i>Chat Admin</a>
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>
    @include('landingpage.layout.js')
</body>

</html>
