@isset($data->id)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update User</h4>
    <form id="form" role="form" class="form-horizontal style-form" method="post" action="{{ route('user.update', ['id' => $data->id]) }}" enctype="multipart/form-data">
        {{ method_field('PUT') }}
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add User</h4>
    <form id="form" role="form" class="form-horizontal style-form" method="post" action="{{ route('user.store') }}" enctype="multipart/form-data">
@endif
    @csrf
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Name</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="name" id="name" onchange="checkfield()" autocomplete="off" value="@isset($data->name){{ $data->name }}@endisset">
        </div>
    </div>
    <div class="form-group input-box" id="username-box">
        <label class="col-sm-3 col-sm-3 control-label">Username</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="username" id="username" onkeyup="checkUniqueUsername()" onchange="checkUniqueUsername()" autocomplete="off" value="@isset($data->username){{ $data->username }}@endisset">
            <span class="username-error" id="uname-error" style="display: none;">Username has been taken</span>
        </div>
    </div>
    @if(!isset($data))
    <div class="form-group form-inline input-box">
        <label class="col-sm-3 col-sm-3 control-label">Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="password" id="password" onchange="checkfield()" autocomplete="new-password">
            <span class="eye" onclick="showpass('pass1')">
                <i class="fa fa-eye-slash" id="togglePassword1"></i>
            </span>
        </div>
    </div>
    <div class="form-group form-inline input-box">
        <label class="col-sm-3 col-sm-3 control-label">Confirm Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="password_retype" id="password_retype" onchange="checkfield()" autocomplete="new-password">
            <span class="eye" onclick="showpass('pass2')">
                <i class="fa fa-eye-slash" id="togglePassword2"></i>
            </span>
        </div>
    </div>
    @endif
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
            <input type="text" class="form-control" name="birthdate" id="birthdate" onchange="checkfield()" value="@isset($data->birthdate){{ $data->birthdate }}@endisset">
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
            <button class="btn @isset($data) btn-theme @else disabled @endif" @if(!isset($data)) disabled @endif id="submit-button" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>

<script>

    $("input#username").on({
        keydown: function(e) {
            if (e.which === 32)
            return false;
        },
        change: function() {
            this.value = this.value.replace(/\s/g, "");
        }
    });

    function showpass(span_id){
        if(span_id == "pass1"){
            var pass = $("#password").attr('type');
            var eye = document.getElementById('togglePassword1');
            var id="password";
        }else{
            var pass = $("#password_retype").attr('type');
            var eye = document.getElementById('togglePassword2');
            var id="password_retype";
        }

        if(pass == "password"){
            eye.classList.add("fa-eye");
            eye.classList.remove("fa-eye-slash");
            document.getElementById(id).type="text";
        }else{
            eye.classList.add("fa-eye-slash");
            eye.classList.remove("fa-eye");
            document.getElementById(id).type="password";
        }
    }

    function checkUniqueUsername(){
        var username = $('#username').val();
        result = false;
        $.ajax({
            url: "{{ route('checkUsernameAvailability') }}",
            type: "get",
            dataType: 'json',
            data: {
                "username": username,
            },success:function(data){
                if(data.text == "notavailable"){
                    document.getElementById("username-box").classList.remove('input-box');
                    document.getElementById("username-box").classList.add('input-box-error');
                    if(document.getElementById("uname-error").style.display != "block"){
                        document.getElementById("uname-error").style.display = "block";                            
                    }
                }else{
                    document.getElementById("username-box").classList.remove('input-box-error');
                    document.getElementById("username-box").classList.add('input-box');
                    if(document.getElementById("uname-error").style.display != "none"){
                        document.getElementById("uname-error").style.display = "none";                            
                    }
                }
                checkfield();
            }
        })
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
        checkfield();
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
        checkfield();
    }

    function checkfield(){
        var name = $('#name').val();
        var username = $('#username').val();
        var password = $('#password').val();
        var retype = $('#password_retype').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var birthdate = $('#birthdate').val();

        if(name != "" && username != "" && password != "" && retype != "" && email != "" && phone != "" && birthdate != "" && password == retype){
            document.getElementById('submit-button').classList.remove('disabled');
            document.getElementById('submit-button').classList.add('btn-theme');
            document.getElementById('submit-button').removeAttribute('disabled');
        }else{
            document.getElementById('submit-button').classList.add('disabled');
            document.getElementById('submit-button').classList.remove('btn-theme');
            document.getElementById('submit-button').setAttribute('disabled','');
        }
    }
</script>