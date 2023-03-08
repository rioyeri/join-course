<script>
    function getProvinces(params=null) {
        var jenisdata = "getProvinces";
        $.ajax({
            url : "{{route('getLocation')}}",
            type : "get",
            dataType: 'json',
            data:{
                jenisdata: jenisdata,
                current_province: params,
            },
        }).done(function (data) {
            $('#address_province').html(data.append);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }

    function getCities(params,current=null) {
        var jenisdata = "getCities";
        $.ajax({
            url : "{{route('getLocation')}}",
            type : "get",
            dataType: 'json',
            data:{
                province: params,
                jenisdata: jenisdata,
                current_city: current,
            },
        }).done(function (data) {
            $('#address_city').html(data.append);
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }
</script>
@isset($data->address_province)
<script>
    $(document).ready(function() {
        var prov = $('#current_prov').val();
        var city = $('#current_city').val();
        console.log(prov,city);
        getProvinces(prov);
        getCities(prov, city);
        $('.select2').select2({
            "width":"100%",
        });
    });
</script>
@endisset

@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Profile</h4>
    <form id="form" role="form" class="form-horizontal style-form" method="post" action="{{ route('user.update', ['id' => $data->id]) }}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    <input type="hidden" name="username" id="username" value="{{ $data->username }}">
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" autocomplete="off" value="@isset($data->name){{ $data->name }}@endisset">
        </div>
    </div>
    <div class="form-group input-box" id="email-box">
        <label class="col-sm-3 col-sm-3 control-label">Email <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="email" class="form-control" name="email" id="email" onchange="checkEmail(this.value)" autocomplete="off" value="@isset($data->email){{ $data->email }}@endisset">
            <span class="email-error" id="email-error" style="display: none;">Email Invalid</span>
        </div>
    </div>
    <div class="form-group input-box" id="phone-box">
        <label class="col-sm-3 col-sm-3 control-label">Whatsapp Number <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="phone" id="phone" onchange="checkPhone(this.value)" autocomplete="off" value="@isset($data->phone){{ $data->phone }}@endisset">
            <span class="phone-error" id="phone-error" style="display: none;">Phone Number Invalid</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Date of Birth <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control datepicker" name="birthdate" id="birthdate" data-date-format='yyyy-mm-dd' value="@isset($data->birthdate){{ $data->birthdate }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Location <span class="text-danger">*</span></label>
        <div class="col-sm-4">
            <select class="form-control select2" parsley-trigger="change" name="address_province" id="address_province" onchange="getCities(this.value)" required>
                <option value="#" disabled selected>Province</option>
            </select>
            @isset($data->address_province)
                <input type="hidden" id="current_prov" value="{{ $data->address_province }}">
            @endisset
        </div>
        <div class="col-sm-5">
            <select class="form-control select2" parsley-trigger="change" name="address_city" id="address_city" required>
                <option value="#" disabled selected>City</option>
            </select>
            @isset($data->address_city)
                <input type="hidden" id="current_city" value="{{ $data->address_city }}">
            @endisset
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-3 col-sm-3">Image Upload <span class="text-danger">*</span></label>
        <div class="col-md-9">
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                    <img src="@isset($data->profilephoto){{ asset('dashboard/assets/users/photos/'.$data->profilephoto) }}@endisset" alt="" />
                </div>
                <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                </div>
                <div>
                    <span class="btn btn-theme02 btn-file">
                        <span class="fileupload-new"><i class="fa fa-paperclip"></i> Select image</span>
                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                        <input type="file" class="default" name="profilephoto" id="profilephoto"/>
                    </span>
                    <a href="javascript:;" class="btn btn-theme04 fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> Remove</a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group input-box" id="personality-box">
        <label class="col-sm-3 col-sm-3 control-label">Personality Type</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="personality" id="personality" autocomplete="off" value="@isset($data->personality){{ $data->personality }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" id="submit-button" type="submit">Update</button>
        </div>
    </div>
</form>

<script>
    // Date Picker
    jQuery('.datepicker').datepicker({
        autoclose: true
    });

    function moveToBox(){
        $('#birthdate').data("datepicker").show();
    }

    const validateEmail = (email) => {
        return email.match(
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
    };

    function checkEmail(email){
        if (!validateEmail(email)) {
            document.getElementById("email-box").classList.remove('input-box');
            document.getElementById("email-box").classList.add('input-box-error');
            if(document.getElementById("email-error").style.display != "block"){
                document.getElementById("email-error").style.display = "block";
            }
        }else{
            document.getElementById("email-box").classList.remove('input-box-error');
            document.getElementById("email-box").classList.add('input-box');
            if(document.getElementById("email-error").style.display != "none"){
                document.getElementById("email-error").style.display = "none";                            
            }
        }
    }

    function checkPhone(phone){
        let isnum = /^\d+$/;
        if(!phone.match(isnum) || phone.length < 9 || phone.length > 13){
            document.getElementById("phone-box").classList.remove('input-box');
            document.getElementById("phone-box").classList.add('input-box-error');
            if(document.getElementById("phone-error").style.display != "block"){
                document.getElementById("phone-error").style.display = "block";
            }
        }else{
            document.getElementById("phone-box").classList.remove('input-box-error');
            document.getElementById("phone-box").classList.add('input-box');
            if(document.getElementById("phone-error").style.display != "none"){
                document.getElementById("phone-error").style.display = "none";                            
            }
        }
    }

    $("#personality").keyup(function() {
        var val = $(this).val()
        $(this).val(val.toUpperCase())
    })
</script>