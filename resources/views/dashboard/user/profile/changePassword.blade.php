<h4 class="mb"><i class="fa fa-angle-right"></i> Change Password : <strong>{{ $data->name }}</strong></h4>
<form id="form" role="form" class="form-horizontal style-form" method="post" action="{{ route('changePassword', ['id' => $data->id]) }}" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    @csrf
    <div class="form-group form-inline input-box">
        <label class="col-sm-3 col-sm-3 control-label">Current Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="old_password" id="old_password" onchange="checkfield()" autocomplete="old-password">
            <span class="eye" onclick="showpass('oldpass')">
                <i class="fa fa-eye-slash" id="togglePasswordold"></i>
            </span>
        </div>
    </div>
    <div class="form-group form-inline input-box">
        <label class="col-sm-3 col-sm-3 control-label">New Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="password" id="password" onchange="checkfield()" onkeyup="checkfield()" autocomplete="new-password">
            <span class="eye" onclick="showpass('pass1')">
                <i class="fa fa-eye-slash" id="togglePassword1"></i>
            </span>
        </div>
    </div>
    <div class="form-group form-inline input-box">
        <label class="col-sm-3 col-sm-3 control-label">Confirm New Password</label>
        <div class="col-sm-9">
            <input type="password" class="form-control" name="password_retype" id="password_retype" onchange="checkfield()" onkeyup="checkfield()" autocomplete="new-password">
            <span class="eye" onclick="showpass('pass2')">
                <i class="fa fa-eye-slash" id="togglePassword2"></i>
            </span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn disabled" disabled id="submit-button" type="submit">Update</button>
        </div>
    </div>
</form>

<script>
    function showpass(span_id){
        if(span_id == "pass1"){
            var pass = $("#password").attr('type');
            var eye = document.getElementById('togglePassword1');
            var id="password";
        }else if(span_id == "pass2"){
            var pass = $("#password_retype").attr('type');
            var eye = document.getElementById('togglePassword2');
            var id="password_retype";
        }else if(span_id == "oldpass"){
            var pass = $("#old_password").attr('type');
            var eye = document.getElementById('togglePasswordold');
            var id="old_password";
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

    function checkfield(){
        var old_password = $('#old_password').val();
        var password = $('#password').val();
        var retype = $('#password_retype').val();

        if(password != "" && retype != "" && old_password != "" && password == retype){
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