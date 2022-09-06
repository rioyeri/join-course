<div class="card-box">
    <h4 class="m-t-0 header-title">Create New Gift Box</h4>
    <div class="row">
        <div class="col-12">
            <div class="p-20">
                <div class="form-group row">
                    <label class="col-2 col-form-label">Account Type<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" required name="account_type" id="account_type" placeholder="Bank (BNI, BCA, Mandiri, Etc) or E-Wallet (Gopay, OVO, Dana, Etc)">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Account Number<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" required name="account_number" id="account_number" placeholder="Account Number or Account ID">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-2 col-form-label">Account Name<span class="text-danger">*</span></label>
                    <div class="col-10">
                        <input type="text" class="form-control" parsley-trigger="change" required name="account_name" id="account_name" placeholder="Account Name or Owner's Account">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group text-right m-b-0">
        <input type="text" id="giftbox_id" value="0">
        <a href="javascript:;" class="btn btn-primary waves-effect waves-light" onclick="addToTable()">Submit</a>
    </div>
</div>

<script>
    $(".datepicker").datepicker()
    // Select2
    $(".select2").select2({
        templateResult: formatState,
        templateSelection: formatState
    });

    // Dropfiy
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop a file here or click',
            'replace': 'Drag and drop or click to replace',
            'remove': 'Remove',
            'error': 'Ooops, something wrong appended.'
        },
        error: {
            'fileSize': 'The file size is too big (1M max).'
        }
    });

    function formatState (opt) {
        if (!opt.id) {
            return opt.text.toUpperCase();
        }

        var optimage = $(opt.element).attr('data-image');
        console.log(optimage)
        if(!optimage){
        return opt.text.toUpperCase();
        } else {
            var $opt = $(
            '<span><img src="' + optimage + '" width="60px" /> ' + opt.text.toUpperCase() + '</span>'
            );
            return $opt;
        }
    };

    function addToTable(){
        account_type = $('#account_type').val();
        account_number = $('#account_number').val();
        account_name = $('#account_name').val();

        giftbox_id = $('#giftbox_id').val();
        count = $('#responsive-datatable tbody tr.trow_giftbox_detail').length;

        if(giftbox_id == 0){
            console.log("if")
            $.ajax({
                url : "{{route('addGiftBoxToTable')}}",
                type : "get",
                dataType: 'json',
                data:{
                    "account_type":account_type,
                    "account_number":account_number,
                    "account_name":account_name,
                    "count":count,
                },
                
            }).done(function (data) {
                $('#tbody-giftboxs').append(data.append);
                resetAll();
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }else{
            console.log("else")
            $("#tbody-giftboxs tr").each(function(){
                var value_number = $(this).find('td:eq(0)').text();
                
                if(value_number == giftbox_id){
                    $('#account_type'+value_number).val(account_type);
                    $(this).find('td:eq(1)').text(account_type);
                    $('#account_number'+value_number).val(account_number);
                    $(this).find('td:eq(2)').text(account_number);
                    $('#account_name'+value_number).val(account_name);
                    $(this).find('td:eq(3)').text(account_name);
                    resetAll();
                    $('#giftbox_id').val(0);
                }
            });
        }
    }

    function resetAll(){
        $('#account_type').val("");
        $('#account_number').val("");
        $('#account_name').val("");
        $('#form-giftboxdetail').html("");
        element = document.getElementById("responsive-datatable");
        element.scrollIntoView();
    }
</script>
