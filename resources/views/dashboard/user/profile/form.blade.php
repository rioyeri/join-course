@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Profile</h4>
    <form id="form" role="form" class="form-horizontal style-form" method="post" action="{{ route('user.update', ['id' => $data->id]) }}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
@endif
    @csrf
    <input type="hidden" name="username" id="username" value="{{ $data->username }}">
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" autocomplete="off" value="@isset($data->name){{ $data->name }}@endisset">
        </div>
    </div>
    <div class="form-group input-box" id="email-box">
        <label class="col-sm-3 col-sm-3 control-label">Email</label>
        <div class="col-sm-9">
            <input type="email" class="form-control" name="email" id="email" onchange="checkEmail(this.value)" autocomplete="off" value="@isset($data->email){{ $data->email }}@endisset">
            <span class="email-error" id="email-error" style="display: none;">Email Invalid</span>
        </div>
    </div>
    <div class="form-group input-box" id="phone-box">
        <label class="col-sm-3 col-sm-3 control-label">Whatsapp Number</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="phone" id="phone" onchange="checkPhone(this.value)" autocomplete="off" value="@isset($data->phone){{ $data->phone }}@endisset">
            <span class="phone-error" id="phone-error" style="display: none;">Phone Number Invalid</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Date of Birth</label>
        <div class="col-sm-9">
            <input type="text" class="form-control datepicker" name="birthdate" id="birthdate" data-date-format='yyyy-mm-dd' value="@isset($data->birthdate){{ $data->birthdate }}@endisset">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-3 col-sm-3">Image Upload</label>
        <div class="col-md-9">
            <div class="fileupload fileupload-new" data-provides="fileupload">
                <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                    <img src="@isset($data->profilephoto) @if(substr($data->profilephoto,0,4) != "http") {{ asset('dashboard/assets/users/photos/'.$data->profilephoto) }} @else{{ $data->profilephoto }}@endif @endisset" alt="" />
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
</script>