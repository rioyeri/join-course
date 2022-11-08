@extends('landingpage.layout.main')
@section('content')
    @include('landingpage.content.hero')

    <main id="main">
        {{-- @include('landingpage.content.clients') --}}
        {{-- @include('landingpage.content.statscounter') --}}
        {{-- @include('landingpage.content.cta') --}}
        @include('landingpage.content.services')
        @include('landingpage.content.aboutus')
        {{-- @include('landingpage.content.portofolio') --}}
        @include('landingpage.content.team')
        @if($content[3]->status == 1)
            @include('landingpage.content.testimonial')
        @endif
        @if($content[4]->status == 1)
            @include('landingpage.content.faq')
        @endif
        @if($content[5]->status == 1)
            @include('landingpage.content.pricing')
        @endif
        @include('landingpage.content.contact')
    </main>
@endsection
