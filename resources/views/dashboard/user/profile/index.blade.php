@extends('dashboard.layout.main')

@section('title')
Your Profile
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('dashboard/additionalplugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Multi Item Selection examples -->
    <link href="{{ asset('dashboard/additionalplugins/datatables/select.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Select2 -->
    <link href="{{ asset('dashboard/additionalplugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert css -->
    <link href="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Magnific Pop-up-->
    <link rel="stylesheet" href="{{ asset('dashboard/additionalplugins/magnific-popup/dist/magnific-popup.css') }}"/>
    {{-- Date Picker --}}
    <link href="{{ asset('dashboard/additionalplugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <!-- File Upload-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            /* border-top: 16px solid #3498db; */
            border-top: 16px solid #f85a40;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
            margin-left:10px;
            margin-right:200px;
            margin-top:10px;
            margin-bottom: 10px;
        }
        @-webkit-keyframes spin {
            0% { -webkit-transform: rotate(0deg); }
            100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 40% !important;
            /* background: #FFFFCC; */
            background: transparent;
            /* border: 1px solid black; */
            border-radius: 3px;
            font-weight: bold;
        }

        img.output{
            object-fit:cover;
            display:block;
            width:100px;
            height:100px;
            display: flex;
        }

        .input-box .col-sm-9 input{
            width: 100%;
        }

        .eye {
            position: absolute;
            margin-top: 8px;
            margin-left: -30px;
            cursor: pointer;
        }

        .username-error {
            position: absolute;
            margin-top: 8px;
            margin-left: 10px;
            /* margin-left: -105px; */
            color: red;
            font-weight: 700;
        }

        .email-error {
            position: absolute;
            margin-top: 8px;
            margin-left: 10px;
            /* margin-left: -105px; */
            color: red;
            font-weight: 700;
        }

        .phone-error {
            position: absolute;
            margin-top: 8px;
            margin-left: 10px;
            /* margin-left: -105px; */
            color: red;
            font-weight: 700;
        }
    </style>
@endsection

@section('content')
<div class="col-lg-12">
    <div class="row content-panel">
        <div class="col-md-4 centered">
            <div class="profile-pic">
                <p><a href="{{ asset('dashboard/assets/users/photos/'.$data->profilephoto) }}" class="image-popup"><img src="{{ asset('dashboard/assets/users/photos/'.$data->profilephoto) }}" class="img-circle"></a></p>
            </div>
        </div>
        <div class="col-md-8 col-md-4 profile-text">
            <h3>{{ $data->name }}</h3>
            <h6 style="font-size: 17px">{{ session('role') }}</h6>
            <p>Your Location : {{ $data->location() }}</p>
            <p>Birthdate : {{ $data->birthdate }}</p>
            <p>Whatsapp Number : {{ $data->phone }}</p>
            <br>
            <p>
                <a class="btn btn-theme" onclick="edit_data({{ session('user_id') }})" data-toggle="modal" data-target="#myModal"><i class="fa fa-gear"></i> Edit Profile</a>
                <a class="btn btn-danger" onclick="changePassword({{ session('user_id') }})" data-toggle="modal" data-target="#myModal"><i class="fa fa-key"></i> Change Password</a>
            </p>
        </div>
    </div>
    <!-- /row -->
</div>
<!-- /col-lg-12 -->
<div class="col-lg-12 mt">
    <div class="row content-panel">
        <div class="panel-heading">
            <ul class="nav nav-tabs nav-justified">
                <li class="active">
                    <a data-toggle="tab" href="#overview">Overview</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#edit">Edit Profile</a>
                </li>
            </ul>
        </div>
        <!-- /panel-heading -->
        <div class="panel-body">
            <div class="tab-content">
                <div id="overview" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="detailed mt">
                                <h4>Your Timeline</h4>
                                <div class="recent-activity">
                                    <div class="activity-icon bg-theme"><i class="fa fa-check"></i></div>
                                    <div class="activity-panel">
                                        <h5>{{ date_format(date_create($data->last_login), "D, d-m-Y H:m:s") }}</h5>
                                        <p>Login Terakhir</p>
                                    </div>
                                    <div class="activity-icon bg-theme02"><i class="fa fa-trophy"></i></div>
                                    <div class="activity-panel">
                                        <h5>5 HOURS AGO</h5>
                                        <p>Mulai aktivitas pembelajaran melalui Flash Academia</p>
                                    </div>
                                    <div class="activity-icon bg-theme04"><i class="fa fa-rocket"></i></div>
                                    <div class="activity-panel">
                                        <h5>{{ date_format(date_create($data->regis_date), "D, d-m-Y") }}</h5>
                                        <p>Bergabung dengan Flash Academia</p>
                                    </div>
                                </div>
                                <!-- /recent-activity -->
                            </div>
                        <!-- /detailed -->
                        </div>
                        <!-- /col-md-6 -->
                        <div class="col-md-6 detailed">
                            <h4>User Stats</h4>
                            <div class="row centered mt mb">
                                <div class="col-sm-4">
                                    <h1><i class="fa fa-money"></i></h1>
                                    <h3>$22,980</h3>
                                    <h6>LIFETIME EARNINGS</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h1><i class="fa fa-trophy"></i></h1>
                                    <h3>37</h3>
                                    <h6>COMPLETED TASKS</h6>
                                </div>
                                <div class="col-sm-4">
                                    <h1><i class="fa fa-shopping-cart"></i></h1>
                                    <h3>1980</h3>
                                    <h6>ITEMS SOLD</h6>
                                </div>
                            </div>
                            <!-- /row -->
                        </div>
                        <!-- /col-md-6 -->
                    </div>
                    <!-- /OVERVIEW -->
                </div>
                <!-- /tab-pane -->
                <div id="edit" class="tab-pane">
                    <div class="row">
                        @if(session('role_id') == 4)
                        <div class="col-lg-8 col-lg-offset-2 detailed">
                            <h4 class="mb">School Information</h4>
                            <form role="form" class="form-horizontal" method="post" action="{{ route('student.update', ['id' => $data->get_student->id]) }}">
                                {{ method_field('PUT') }}
                                @csrf
                                <input type="hidden" name="type" value="profile">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">School Name</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="school_name" id="school_name" value="@isset($data->get_student->school_name){{ $data->get_student->school_name }}@endisset">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Grade</label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2" parsley-trigger="change" name="student_grade" id="student_grade">
                                            <option value="#" disabled selected>-- Choose --</option>
                                            @foreach ($grades as $grade)
                                                @isset($data->get_student->student_grade)
                                                    @if ($grade->id == $data->get_student->student_grade)
                                                        <option value="{{$grade->id}}" selected>{{$grade->name}}</option>
                                                    @else
                                                        <option value="{{$grade->id}}" >{{$grade->name}}</option>
                                                    @endif
                                                @else
                                                    <option value="{{$grade->id}}" >{{$grade->name}}</option>
                                                @endisset
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-theme" type="submit">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endif
                        @if(session('role_id') == 5)
                        <div class="col-lg-8 col-lg-offset-2 detailed mt">
                            <h4 class="mb">Teacher's Basic Information</h4>
                            <form role="form" class="form-horizontal" method="post" action="{{ route('updateTeacherProfile', ['id' => $data->get_teacher->id]) }}">
                                {{ method_field('PUT') }}
                                @csrf
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Your Title</label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" name="title" id="title" placeholder="Misal: Guru Matematika, Pelatih Renang" value="{{ $data->get_teacher->title }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Description</label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Misal: Saya telah berpengalaman dalam mengajar bidang ini selama 5 tahun">@isset($data->get_teacher->description){{ $data->get_teacher->description }}@endisset</textarea>
                                    </div>
                                </div>
                                <br>
                                <h4 class="mb">What you teach?</h4>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Subjects</label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2 select2-multiple" multiple="multiple" multiple parsley-trigger="change" name="teacher_subjects[]" id="teacher_subjects" data-placeholder="-- What subject do you teach--">
                                            @foreach ($courses as $course)
                                                @isset($data)
                                                    @if(in_array($course->id, $exist_course))
                                                        <option value="{{$course->id}}" selected>{{$course->name}}</option>
                                                    @else
                                                        <option value="{{$course->id}}" >{{$course->name}}</option>
                                                    @endif
                                                @else
                                                    <optgroup label="{{ $course->name }}">
                                                        <option value="{{$course->id}}" >{{$course->name}}</option>
                                                    </optgroup>
                                                @endisset
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <br>
                                <h4 class="mb">Your Pricing</h4>
                                <div class="form-group">
                                    <label class="col-lg-1 control-label">Package</label>
                                    <div class="col-lg-4">
                                        <select class="form-control select2" parsley-trigger="change" id="package" >
                                            <option value="#" disabled selected>-- Choose --</option>
                                            @foreach ($packages as $package)
                                                <option value="{{$package->id}}" >{{$package->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <label class="col-lg-1 control-label">Price</label>
                                    <div class="col-lg-4">
                                        <input type="number" class="form-control" id="price" value="0">
                                    </div>
                                    <div class="col-lg-2">
                                        <a class="btn btn-theme" onclick="addToTable()"><i class="fa fa-plus"></i> Add</a>
                                    </div>
                                </div>

                                <div id="table_packages" @if(count($exist_packages) == 0) style="display:none" @endif>
                                    <div class="adv-table">
                                        <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-package">
                                            <thead>
                                                <th>No</th>
                                                <th>Package Name</th>
                                                <th>Price</th>
                                                <th>Options</th>
                                            </thead>
                                            <tbody id="table-body-package">
                                                @php($i=1)
                                                @foreach($exist_packages as $key)
                                                    <tr style="width:100%" id="trow{{ $i }}" class="trow">
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $key->get_package->name }}</td>
                                                        <input type="hidden" name="package_id[]" id="package_id{{ $i }}" value="{{ $key->package_id }}">
                                                        <td class="text-right">Rp {{ number_format($key->price,2,",",".") }}</td>
                                                        <input type="hidden" name="package_price[]" id="package_price{{ $i }}" value="{{ $key->price }}">
                                                        <td class="text-center"><a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItemPackage({{ $i }})">Delete</a></td>
                                                    </tr>
                                                    @php($i++)
                                                @endforeach        
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-2 col-lg-10">
                                        <button class="btn btn-theme" type="submit">Save</button>
                                    </div>
                                </div>

                            </form>
                        </div>
                        @endif
                        @if(session('role_id') != 4 && session('role_id') != 5)
                            <p class="centered">-- This page available for Students and Teachers Only. --</p>
                        @endif
                        <!-- /col-lg-8 -->
                    </div>
                    <!-- /row -->
                </div>
                <!-- /tab-pane -->
            </div>
        <!-- /tab-content -->
        </div>
        <!-- /panel-body -->
    </div>
    <!-- /col-lg-12 -->
</div>
<!-- /row -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Flash Academia</h4>
            </div>
            <div class="modal-body" id="view-form">
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    <!-- Required datatable js -->
    <script src="{{ asset('dashboard/additionalplugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalplugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('dashboard/additionalplugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalplugins/datatables/responsive.bootstrap4.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('dashboard/additionalplugins/select2/js/select2.min.js') }}" type="text/javascript"></script>

    <!-- Sweet Alert Js  -->
    <script src="{{ asset('dashboard/additionalplugins/sweet-alert/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('dashboard/additionalpages/jquery.sweet-alert.init.js') }}"></script>

    <!-- Magnific popup -->
    <script type="text/javascript" src="{{ asset('dashboard/additionalplugins/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>

    <!-- Date Picker -->
    <script src="{{ asset('dashboard/additionalplugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <!-- File Upload -->
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.js') }}"></script>
@endsection

@section('script-js')
<script>
$(document).ready(function() {
    $('.image-popup').magnificPopup({
        type: 'image',
    });

    $(".select2").select2({
        templateResult: formatState,
        templateSelection: formatState,
        width:'100%',
    });

    function formatState (opt) {
        if (!opt.id) {
            return opt.text;
        }

        var optimage = $(opt.element).attr('data-image');
        console.log(optimage)
        if(!optimage){
        return opt.text;
        } else {
            var $opt = $(
            '<span><img src="' + optimage + '" width="60px" /> ' + opt.text + '</span>'
            );
            return $opt;
        }
    };
})

    function edit_data(id){
        $.ajax({
            url : "/user/"+id+"/edit",
            type : "get",
            dataType: 'json',
            data: {
                "type": "profile",
            }
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function changePassword(id){
        $.ajax({
            url : "/user/"+id+"/edit",
            type : "get",
            dataType: 'json',
            data: {
                "type": "changePassword",
            }
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function addToTable(){
        var style_display = document.getElementById('table_packages').style.display;    
        if(style_display == 'none'){
            document.getElementById('table_packages').style.display = 'block';
        }
        var package_id = $('#package').val();
        var price = $('#price').val();
        select = document.getElementById("package");
        var package_name = select.options[select.selectedIndex].text;
        count = $('#table-package tbody tr.trow').length+1;

        duplicate = 0;
        $("#table-body-package tr").each(function(){
            var value_count = $(this).find('td:eq(0)').text();
            var value_package = $('#package_id'+value_count).val();
            if(value_package == package_id){
                duplicate++;
            }
        });

        if(duplicate == 0){
            var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td>'+count+'</td><td>'+package_name+'</td><input type="hidden" name="package_id[]" id="package_id'+count+'" value="'+package_id+'"><td class="text-right">Rp '+number_format(price,2,",",".")+'</td><input type="hidden" name="package_price[]" id="package_price'+count+'" value="'+price+'"><td class="text-center"><a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItemPackage('+count+')" >Delete</a></td></tr>';
            $('#table-body-package').append(append);
        }else{
            $("#table-body-package tr").each(function(){
                var value_count = $(this).find('td:eq(0)').text();
                var value_package = $('#package_id'+value_count).val();
                if(value_package == package_id){
                    $(this).find('td:eq(2)').text('Rp '+number_format(price,2,",","."));
                    $('#package_price'+value_count).val(price);
                    console.log($('#package_price'+value_count).val());
                }
            });
        }
        resetFormPackage();
    }

    function resetFormPackage(){
        $('#package').val('#').change();
        $('#price').val("");
    }

    function deleteItemPackage(id){
        $('#trow'+id).remove();
    }
</script>
@endsection