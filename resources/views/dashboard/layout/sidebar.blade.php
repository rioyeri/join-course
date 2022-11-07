<!-- **********************************************************************************************************************************************************
MAIN SIDEBAR MENU
*********************************************************************************************************************************************************** -->
<!--sidebar start-->
@php
    use App\Models\MenuMapping;
    use App\Models\Modul;
    use App\Models\SubModul;
    use App\Models\Teacher;
    use App\Models\ContentProfile;
    use App\Models\User;

    $phone_format = User::getFormatWANumber(ContentProfile::where('id',5)->first()->content);
    $phone_redirect = "https://api.whatsapp.com/send?phone=".$phone_format;
@endphp
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">
            <p class="centered"><a href="{{ session('photo') }}" class="image-popup"><img src="{{ session('photo') }}" class="img-circle" width="80"></a></p>
            <h5 class="centered">{{ session('username') }}</h5>
            <h6 class="centered">{{ session('name') }}</h6>
            <h4 class="centered">{{ session('role') }}</h4>
            {{-- @isset(session('role_id')) --}}
                @if(session('role_id') == 5 && Teacher::where('user_id', session('user_id'))->first()->status != 1) 
                    <h6 class="centered" style="color:red;padding: 0 10% 0 10%;">(As a Teacher you need a approval by Admin)</h6>
                @endif
            {{-- @endif --}}
            <div class="centered">
                <a href="{{ route('viewProfile') }}" class="btn-setting" title="Setting"><i class="fa fa-gear"></i></a>
                <a href="{{ $phone_redirect }}" class="btn-setting" target="_blank" title="Whatsapp Admin"><i class="fa fa-whatsapp"></i></a>
            </div>
            <li class="mt">
                <a @if(substr($page, 0,2) == "DS") class="active" @endif href="{{ route('getHome') }}">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if(session('role_id') == 1 || session('role_id') == 2)
                @foreach (Modul::getAllModul("sidebar") as $modul)
                    <li class="sub-menu">
                        <a @if(substr($page, 0,2) == $modul->modul_id) class="active" @endif href="javascript:;">
                            <i class="{{$modul->modul_icon}}"></i>
                            <span>{{$modul->modul_desc}}</span> <span class="menu-arrow"></span>
                        </a>
                        <ul class="sub">
                            @foreach (SubModul::getSub($modul->modul_id) as $sub)
                                <li @if($page == $sub->submodul_id) class="active" @endif><a href="{{route(''.$sub->submodul_page.'')}}"  class="nav-link"><strong>{{$sub->submodul_desc}}</strong></a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @else
                @foreach (MenuMapping::getHeadModul(session('role_id')) as $modul)
                    <li class="sub-menu">
                        <a @if(substr($page, 0,2) == $modul->modul_id) class="active" @endif href="javascript:;">
                            <i class="{{$modul->modul_icon}}"></i>
                            <span>{{$modul->modul_desc}}</span> <span class="menu-arrow"></span>
                        </a>
                        <ul class="sub">
                            @foreach (MenuMapping::getModul(session('role_id'),$modul->modul_id) as $sub)
                                <li @if($page == $sub->submodul_id) class="active" @endif><a href="{{route(''.$sub->submodul_page.'')}}" class="nav-link">{{$sub->submodul_desc}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            @endif
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->