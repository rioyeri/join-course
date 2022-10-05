@extends('landingpage.layout.main')
@section('content')
    @include('landingpage.content.hero')

    <main id="main">
        {{-- @include('landingpage.content.aboutus') --}}
        {{-- @include('landingpage.content.clients') --}}
        {{-- @include('landingpage.content.statscounter') --}}
        {{-- @include('landingpage.content.cta') --}}
        @include('landingpage.content.services')
        {{-- @include('landingpage.content.portofolio') --}}
        @include('landingpage.content.team')
        @include('landingpage.content.testimonial')
        {{-- @include('landingpage.content.pricing') --}}
        @include('landingpage.content.faq')
        {{-- @include('landingpage.content.blog') --}}
        @include('landingpage.content.contact')
    </main>
@endsection
