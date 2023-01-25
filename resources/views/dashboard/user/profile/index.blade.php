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
    <!-- Time Picker -->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-timepicker/compiled/timepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-datetimepicker/css/datetimepicker.css') }}" />
    <!-- File Upload-->
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/lib/bootstrap-fileupload/bootstrap-fileupload.css') }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        #loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            /* border-top: 16px solid #3498db; */
            border-top: 16px solid var(--color-secondary);
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
                {{-- <p><a href="{{ asset('dashboard/assets/users/photos/'.$data->profilephoto) }}" class="image-popup"><img src="{{ asset('dashboard/assets/users/photos/'.$data->profilephoto) }}" class="img-circle"></a></p> --}}
                <p><a href="{{ asset($profilephoto) }}" class="image-popup"><img src="{{ asset($profilephoto) }}" class="img-circle"></a></p>
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
                                    {{-- <div class="activity-icon bg-theme02"><i class="fa fa-trophy"></i></div>
                                    <div class="activity-panel">
                                        <h5>5 HOURS AGO</h5>
                                        <p>Mulai aktivitas pembelajaran melalui Flash Academia</p>
                                    </div> --}}
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
                        @if(session('role_id') == 4)
                            <div class="col-md-6 detailed">
                                <h4>User Stats</h4>
                                <div class="row centered mt mb">
                                    <div class="col-sm-12">
                                        <h1><i class="fa fa-trophy" style="color:var(--color-secondary)"></i></h1>
                                        <h3>{{ number_format($stats->order_count, 0, ",", ".") }}</h3>
                                        <h6>COMPLETED COURSE</h6>
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                        @endif
                        @if(session('role_id') == 5)
                            <div class="col-md-6 detailed">
                                <h4>User Stats</h4>
                                <div class="row centered mt mb">
                                    {{-- <div class="col-sm-4">
                                        <h1><i class="fa fa-money"></i></h1>
                                        <h3>Rp {{ number_format($stats->income, 2, ",", ".") }}</h3>
                                        <h6>LIFETIME EARNINGS</h6>
                                    </div> --}}
                                    <div class="col-sm-4">
                                        <h1><a href="" onclick="getHistory({{ $data->get_teacher->id }})" data-toggle="modal" data-target="#myModal"><i class="fa fa-trophy" style="color:#008374"></i></a></h1>
                                        <h3><a href="" onclick="getHistory({{ $data->get_teacher->id }})" data-toggle="modal" data-target="#myModal" style="color:#008374">{{ number_format($stats->order_count, 0, ",", ".") }}</a></h3>
                                        <h6>COMPLETED COURSE</h6>
                                    </div>
                                    <div class="col-sm-4">
                                        <h1><i class="fa fa-star" style="color:#FFD700;"></i></h1>
                                        <h3>{{ $stats->rate }}</h3>
                                        <h6>YOUR RATE</h6>
                                    </div>
                                    <div class="col-sm-4">
                                        <h1><a href="" onclick="getReview({{ $data->get_teacher->id }})" data-toggle="modal" data-target="#myModal"><i class="fa fa-comment-o" style="color:#ed5565"></i></a></h1>
                                        <h3><a href="" onclick="getReview({{ $data->get_teacher->id }})" data-toggle="modal" data-target="#myModal" style="color:#ed5565">{{ $stats->review_count }}</a></h3>
                                        <h6>REVIEW COUNTS</h6>
                                    </div>
                                </div>
                                <!-- /row -->
                            </div>
                        @endif
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
                                <div class="form-group">
                                    <label class="col-sm-2 col-sm-2 control-label">Availability</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select2" parsley-trigger="change" name="availability" id="availability">
                                            @isset($data->get_teacher->availability)
                                                <option value="0" @if($data->get_teacher->availability == 0) selected @endif>Offline</option>
                                                <option value="1" @if($data->get_teacher->availability == 1) selected @endif>Online</option>
                                                <option value="2" @if($data->get_teacher->availability == 2) selected @endif>Online & Offline</option>
                                            @else
                                                <option value="0">Offline</option>
                                                <option value="1">Online</option>
                                                <option value="2" selected>Online & Offline</option>
                                            @endisset
                                        </select>
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
                                {{-- <h4 class="mb">Your Pricing</h4>
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
                                </div> --}}
                                <h4 class="mb">Your Schedules</h4>
                                <input type="hidden" id="schedule_id" value="0">
                                <input type="hidden" id="row_number" value="0">

                                <div class="form-group">
                                    <label class="col-sm-1 control-label" style="padding-left:-100px;">Schedule</label>
                                    <div class="col-lg-3">
                                        <select class="form-control select2" id="day">
                                            <option value="#" disabled selected>-- Day --</option>
                                            @foreach ($days as $day)
                                                <option value="{{ $day->id }}">{{ $day->day_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-3" style="margin-left:-25px;">
                                        <input type="text" class="form-control timepicker-24" id="start" value="" placeholder="Time Start" style="height: 29px;">
                                        <span id="span_time" class="input-group-btn add-on" onclick="moveToTimeBox('start')" style="padding-right: 32px; padding-top: 7px;">
                                            <button class="btn btn-theme btn-sm" type="button" style="height: 29px;"><i class="fa fa-clock-o"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-3" style="margin-left:-25px;">
                                        <input type="text" class="form-control timepicker-24" id="end" value="" placeholder="Time End" style="height: 29px;">
                                        <span id="span_time" class="input-group-btn add-on" onclick="moveToTimeBox('end')" style="padding-right: 32px; padding-top: 7px;">
                                            <button class="btn btn-theme btn-sm" type="button" style="height: 29px;"><i class="fa fa-clock-o"></i></button>
                                        </span>
                                    </div>
                                    <div class="col-sm-1" id="button_adddetail" style="margin-left:-25px; margin-right: 20px; display:block;">
                                        <a class="btn btn-sm btn-theme02" onclick="addToTable()">Add</a>
                                    </div>
                                    <div class="col-sm-1" id="button_updatedetail" style="margin-left:-25px; margin-right: 20px; display:none;">
                                        <a class="btn btn-sm btn-theme02" onclick="addToTable()">Update</a>
                                    </div>
                                    <div class="col-sm-1">
                                        <a class="btn btn-sm btn-danger" onclick="clearForm()">Clear</a>
                                    </div>
                                </div>

                                <br>
                                <div id="table_schedule_detail">
                                    <div class="adv-table">
                                        <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-schedule-detail">
                                            <thead>
                                                <th>No</th>
                                                <th>Day</th>
                                                <th>Time Start</th>
                                                <th>Time End</th>
                                                <th>Option</th>
                                            </thead>
                                            <tbody id="table-body-detail">
                                                @isset($details)
                                                    @php($i=1)
                                                    @foreach($details as $key)
                                                        <input type="hidden" id="schedule_id{{ $i }}" value="{{ $key->id }}">
                                                        <tr style="width:100%" id="trow{{ $i }}" class="trow">
                                                            <td>{{ $i }}</td>
                                                            <td>{{ $key->get_day->day_name }}</td>
                                                            <input type="hidden" name="day_id[]" id="day_id{{ $i }}" value="{{ $key->day_id }}">
                                                            <td>{{ $key->time_start }}</td>
                                                            <input type="hidden" name="time_start[]" id="time_start{{ $i }}" value="{{ $key->time_start }}">
                                                            <td>{{ $key->time_end }}</td>
                                                            <input type="hidden" name="time_end[]" id="time_end{{ $i }}" value="{{ $key->time_end }}">
                                                            <td class="text-center">
                                                                <a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row({{ $i }})">Edit</a>
                                                                <a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row({{ $i }})">Delete</a>
                                                            </td>
                                                        </tr>
                                                        @php($i++)
                                                    @endforeach
                                                @endisset
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-12">
                                        <button class="btn btn-theme btn-block" type="submit">Save</button>
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
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
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

    <!-- Time Picker -->
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-daterangepicker/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dashboard/lib/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
    
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

        $('.timepicker-24').timepicker({
            autoclose: true,
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false
        });

        function moveToTimeBox(id){
            $('#'+id).timepicker({
                autoclose: true,
                minuteStep: 1,
                showSeconds: true,
                showMeridian: false
            });;
        }

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

    function edit_row(row){
        $("#table-body-detail tr").each(function(){
            var number = $(this).find('td:eq(0)').text();
            if(number == row){
                var schedule_id = $('#schedule_id'+row).val();
                var day_id = $('#day_id'+row).val();
                var time_start = $(this).find('td:eq(2)').text();
                var time_end = $(this).find('td:eq(3)').text();

                $('#day').val(day_id).change();
                $('#start').val(time_start);
                $('#end').val(time_end)
                $('#schedule_id').val(schedule_id);
                $('#row_number').val(number);

                document.getElementById('button_adddetail').style.display = 'none';
                document.getElementById('button_updatedetail').style.display = 'block';
            }
        });
    }

    function addToTable(){
        var day = $('#day').val();
        var start = $('#start').val();
        var end = $('#end').val();
        var schedule_id = $('#schedule_id').val();
        var schedule_no = $('#row_number').val();

        daySelect = document.getElementById("day");
        var day_name = daySelect.options[daySelect.selectedIndex].text;

        duplicate = 0;
        $("#table-body-detail tr").each(function(){
            var value_count = $(this).find('td:eq(0)').text();
            if(schedule_no == value_count){
                duplicate++;
            }
        });

        if(day != null && start != "" && end != ""){
            console.log(day, start, end, schedule_id, schedule_no);

            if(schedule_id == 0){
                var i = $('#table-schedule-detail tbody tr.trow').length + 1;
                var append = '<tr style="width:100%" id="trow'+i+'" class="trow">'
                append += '<td>'+i+'</td>';
                append += '<td>'+day_name+'</td>';
                append += '<input type="hidden" name="day_id[]" id="day_id'+i+'" value="'+day+'">';
                append += '<td>'+start+'</td>';
                append += '<input type="hidden" name="time_start[]" id="time_start'+i+'" value="'+start+'">';
                append += '<td>'+end+'</td>';
                append += '<input type="hidden" name="time_end[]" id="time_end'+i+'" value="'+end+'">';
                append += '<td class="text-center">';
                append += '<a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row('+i+')"> Edit</a> ';
                append += '<a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row('+i+')"> Delete</a></td></tr>';
                $('#table-body-detail').append(append);
            }else{
                $("#table-body-detail tr").each(function(){
                    var value_count = $(this).find('td:eq(0)').text();
                    if(value_count == schedule_no){
                        $(this).find('td:eq(1)').text(day_name);
                        $('#day_id'+value_count).val(day);

                        $(this).find('td:eq(2)').text(start);
                        $('#time_start'+value_count).val(start);

                        $(this).find('td:eq(3)').text(end);
                        $('#time_end'+value_count).val(end);
                    }
                });
            }
            clearForm();
        }
    }

    function delete_row(id){
        $('#trow'+id).remove();
        correctionNumber();
    }

    function clearForm(){
        $('#schedule_id').val("");
        $('#row_number').val("");
        $('#day').val('#').change();
        $('#start').val("");
        $('#end').val("");
        document.getElementById('button_adddetail').style.display = 'block';
        document.getElementById('button_updatedetail').style.display = 'none';
    }

    function correctionNumber(){
        var i = 1;
        $("#table-schedule-detail tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }

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

    function getReview(id){
        $.ajax({
            url : "home/"+id,
            type : "get",
            dataType: 'json',
            data: {
                modal_type: "review",
            }
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getHistory(id){
        $.ajax({
            url : "/home/"+id,
            type : "get",
            dataType: 'json',
            data: {
                modal_type: "order",
            }
        }).done(function (data) {
            $('#view-form').html(data);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    // function addToTable(){
    //     var style_display = document.getElementById('table_packages').style.display;    
    //     if(style_display == 'none'){
    //         document.getElementById('table_packages').style.display = 'block';
    //     }
    //     var package_id = $('#package').val();
    //     var price = $('#price').val();
    //     select = document.getElementById("package");
    //     var package_name = select.options[select.selectedIndex].text;
    //     count = $('#table-package tbody tr.trow').length+1;

    //     duplicate = 0;
    //     $("#table-body-package tr").each(function(){
    //         var value_count = $(this).find('td:eq(0)').text();
    //         var value_package = $('#package_id'+value_count).val();
    //         if(value_package == package_id){
    //             duplicate++;
    //         }
    //     });

    //     if(duplicate == 0){
    //         var append = '<tr style="width:100%" id="trow'+count+'" class="trow"><td>'+count+'</td><td>'+package_name+'</td><input type="hidden" name="package_id[]" id="package_id'+count+'" value="'+package_id+'"><td class="text-right">Rp '+number_format(price,2,",",".")+'</td><input type="hidden" name="package_price[]" id="package_price'+count+'" value="'+price+'"><td class="text-center"><a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItemPackage('+count+')" >Delete</a></td></tr>';
    //         $('#table-body-package').append(append);
    //     }else{
    //         $("#table-body-package tr").each(function(){
    //             var value_count = $(this).find('td:eq(0)').text();
    //             var value_package = $('#package_id'+value_count).val();
    //             if(value_package == package_id){
    //                 $(this).find('td:eq(2)').text('Rp '+number_format(price,2,",","."));
    //                 $('#package_price'+value_count).val(price);
    //                 console.log($('#package_price'+value_count).val());
    //             }
    //         });
    //     }
    //     resetFormPackage();
    // }

    // function resetFormPackage(){
    //     $('#package').val('#').change();
    //     $('#price').val("");
    // }

    // function deleteItemPackage(id){
    //     $('#trow'+id).remove();
    // }
</script>
@endsection