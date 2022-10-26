<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
<form class="form-horizontal style-form" method="post" action="">
    {{ method_field('PUT') }}
    @csrf
    <h4 class="mb"><i class="fa fa-angle-right"></i> Report for Order ID : <strong>{{ $data->order_id }}</strong></h4>
    @if(array_search("DSRPU",$submoduls))
    <h5 id="row_title" style="display:none;">Update Schedule no : <strong><span id="row_number"></span></strong> <a class="btn btn-xs btn-danger" href="javascript:;" onclick="resetForm()"><i class="fa fa-eraser"></i> Cancel Editing</a></h5>

    <input type="hidden" id="order_id" value="{{ $data->id }}">
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Report Title</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="title" value="">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Report File</label>
        <div class="col-sm-9">
            <input type="file" class="form-control" id="file" value="">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <a class="btn btn-md btn-theme" onclick="addToTable()" id="save_button"><i class="fa fa-plus"></i> Save Report</a>
        </div>
    </div>
    @endif

    <div id="table_report">
        <div class="adv-table">
            <table cellpadding="0" cellspacing="0" class="table table-bordered dt-responsive wrap" id="table-report">
                <thead>
                    <th>No</th>
                    <th>Report Date</th>
                    <th>Title</th>
                    <th>File</th>
                    @if(array_search("DSRPD", $submoduls))
                    <th>Options</th>
                    @endif
                </thead>
                <tbody id="table-body-report">
                    @php($i=1)
                    @foreach($reports as $key)
                        <tr style="width:100%" id="trow{{ $i }}" class="trow">
                            <td style="width:5%">{{ $i }}</td>
                            <td style="width:25%">{{ $key->created_at->format('Y-m-d H:i:s') }}</td>
                            <td style="width:20%">{{ $key->title }}</a></td>
                            <td style="width:30%"><a href="{{ asset('dashboard/assets/order/'.substr($data->order_id,1).'/'.$key->file) }}" target="_blank"><i class="fa fa-file-text-o"></i> {{ $key->file }}</a></td>
                            @if(array_search("DSRPD", $submoduls))
                            <td class="text-center" style="width:20%">
                                <a href="javascript:;" type="button" class="btn btn-danger btn-trans waves-effect w-xs waves-danger m-b-5" onclick="deleteItem({{ $key->id }}, {{ $i }})">Delete</a>
                            </td>
                            @endif
                        </tr>
                        @php($i++)
                    @endforeach        
                </tbody>
            </table>
        </div>
    </div>
</form>

<script>
    function addToTable(){
        var token = $("meta[name='csrf-token']").attr("content");
        var order_id = $('#order_id').val();
        var title = $('#title').val();
        // var file = $('#file').val();
        var file = document.getElementById('file').files[0];
        if(file == undefined){
            file == "";
        }

        var form_data = new FormData();
        form_data.append('order_id', order_id);
        form_data.append('title', title);
        form_data.append('file', file);

        console.log(form_data)

        $.ajax({
            url : "{{route('home.store')}}",
            type : "post",
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
        }).done(function (data) {
            $('#table-body-report').append(data.append);
            resetForm();
            correctionNumber();
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });

    }

    function resetForm(){
        $('#title').val("");
        $('#file').val("");
    }

    function deleteItem(report_id,id){
        console.log(report_id, id);
        var token = $("meta[name='csrf-token']").attr("content");

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger m-l-10',
            buttonsStyling: false
        }).then(function () {
            $.ajax({
                url: "home/"+report_id,
                type: 'DELETE',
                data: {
                    "_token": token,
                    "type" : "report",
                },
            }).done(function (data) {
                swal(
                    'Deleted!',
                    'Your data has been deleted.',
                    'success'
                )
                $('#trow'+id).remove();
                correctionNumber();
            }).fail(function (msg) {
                swal(
                    'Failed',
                    'Failed to delete',
                    'error'
                )
            });

        }, function (dismiss) {
            // dismiss can be 'cancel', 'overlay',
            // 'close', and 'timer'
            if (dismiss === 'cancel') {
                swal(
                    'Cancelled',
                    'Your data is safe :)',
                    'error'
                )
            }
        })
    }

    function correctionNumber(){
        var i = 1;
        $("#table-body-report tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }
</script>