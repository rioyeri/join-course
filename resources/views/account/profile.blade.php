@extends('layout.main')
@php
    use App\Modul;
    use App\SubModul;
@endphp

@section('css')

@endsection

@section('judul')
Data Profile
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <div class="card-box">
                <div class="bg-picture card-box">
                    <div class="profile-info-name">
                        <img src="{{ asset('assets/images/user/foto/'.$user->foto_profil) }}"
                                class="img-thumbnail" alt="profile-image">

                        <div class="profile-info-detail">
                            <h4 class="m-0">{{$user->name}}</h4>
                            <p class="text-muted m-b-20"><i>{{$user->rolemapping->role->role_name}}</i></p>
                            <div class="button-list m-t-20">
                                <button type="button" class="btn btn-facebook btn-sm waves-effect waves-light">
                                    <i class="fa fa-facebook"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-twitter waves-effect waves-light">
                                    <i class="fa fa-twitter"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-linkedin waves-effect waves-light">
                                    <i class="fa fa-linkedin"></i>
                                </button>

                                <button type="button" class="btn btn-sm btn-dribbble waves-effect waves-light">
                                    <i class="fa fa-dribbble"></i>
                                </button>

                            </div>
                            <p></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-left">
                                        <p class="text-muted font-13"><strong>Username :</strong> <span class="m-l-15">{{$user->username}}</span></p>

                                        <p class="text-muted font-13"><strong>Alamat :</strong> <span class="m-l-15">{{$user->address}}</span></p>

                                        <p class="text-muted font-13"><strong>Email :</strong> <span class="m-l-15">{{$user->email}}</span></p>

                                        <p class="text-muted font-13"><strong>No HP :</strong> <span class="m-l-15">{{$user->phone}}</span></p>

                                        <p class="text-muted font-13"><strong>Tempat Lahir :</strong> <span class="m-l-15">{{$user->tmpt_lhr}}</span></p>

                                        <p class="text-muted font-13"><strong>Tanggal Lahir :</strong> <span class="m-l-15">{{$user->tgl_lhr}}</span></p>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-1"></div>
    </div>
@endsection

@section('js')

@endsection

@section('script-js')

@endsection
