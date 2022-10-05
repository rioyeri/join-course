<!DOCTYPE html>
<html>
@include('landingpage.layout.head')

<body>
    {{-- @include('landingpage.layout.header') --}}
    @include('landingpage.layout.navigation')
    @yield('content')
    @include('landingpage.layout.footer')
    <a href="#" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>
    <div id="preloader"></div>
    @include('landingpage.layout.js')
</body>

</html>
