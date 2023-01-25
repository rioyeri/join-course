@isset($data)
    <h4 class="mb"><i class="fa fa-angle-right"></i> Update Package</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('contentpromo.update', ['id' => $data->id]) }}">
        {{ method_field('PUT') }}
        <input type="hidden" id="promo_id" value="{{ $data->id }}">
@else
    <h4 class="mb"><i class="fa fa-angle-right"></i> Add Package</h4>
    <form class="form-horizontal style-form" method="post" action="{{ route('contentpromo.store') }}">
@endif
    @csrf
    <p class="text-muted font-14">
        <label class="col-4 col-form-label">( <span class="text-danger">*</span> ) is a required field</label>
    </p>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Package Name  <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" name="package_id" id="package_id" onchange="getPackage(this.value)" required>
                <option value="#" selected disabled>-- Choose --</option>
                @foreach ($packages as $package)
                    @isset($data->package_id)
                        @if($data->package_id == $package->id)
                            <option value="{{ $package->id }}" selected>{{$package->name}}</option>
                        @else
                            <option value="{{ $package->id }}">{{$package->name}}</option>
                        @endif
                    @else
                        <option value="{{ $package->id }}">{{$package->name}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group" style="margin-top:-30px;">
        <label class="col-sm-4 col-sm-4 control-label">Price : <span name="price" id="price"></span></label>
        <label class="col-sm-4 col-sm-4 control-label">Discount % : <span name="discount_rate" id="discount_rate"></span></label>
        <label class="col-sm-4 col-sm-4 control-label">Disc Price : <span name="discount_price" id="discount_price"></span></label>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Link Text  <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="link_text" id="link_text" placeholder="Pesan sekarang / Ambil Promo" value="@isset($data->link_text){{ $data->link_text }}@endisset" required>
        </div>
    </div>
    {{-- <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Link</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="link" id="link" placeholder="https://" value="@isset($data->link){{ $data->link }}@endisset">
        </div>
    </div> --}}
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Icon  <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" name="icon" id="icon" required>
                <option value="#" selected disabled>-- Choose --</option>
                @foreach ($icons as $icon)
                    @isset($data->icon)
                        @if($data->icon == $icon->icon)
                            <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}" selected><i class="{{ $icon->icon }}"></i>{{$icon->icon}}</option>
                        @else
                            <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}"><i class="{{ $icon->icon }}"></i>{{$icon->icon}}</option>
                        @endif
                    @else
                        <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}"><i class="{{ $icon->icon }}"></i>{{$icon->icon}}</option>
                    @endisset
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Category  <span class="text-danger">*</span></label>
        <div class="col-sm-9">
            <select class="form-control select2" name="category" id="category" required>
                @isset($data->category)
                    <option value="0" @if($data->category == 0) selected @endif>Regular Offer</option>
                    <option value="1" @if($data->category == 1) selected @endif>Best Offer</option>
                @else
                    <option value="0">Regular Offer</option>
                    <option value="1">Best Offer</option>
                @endisset
            </select>
        </div>
    </div>

    <hr style="border: 1px solid #C5C5C5;">

    <h5 class="mb" id="title-edit"><strong>Add Promo Detail</strong></h5>
    <input type="hidden" id="detail_id" value="0">
    <input type="hidden" id="row_number" value="0">
    <div class="form-group-sm">
        <label class="col-sm-1 col-sm-1 control-label">Detail</label>
        <div class="col-sm-3">
            <select class="form-control select2" id="status">
                <option value="1">Included</option>
                <option value="0">Excluded</option>
            </select>
        </div>
        <div class="col-sm-6" style="margin-left:-25px;">
            <input type="text" class="form-control" id="feature" placeholder="feature" value="">
        </div>
        <div class="col-sm-1" id="button_adddetail" style="margin-left:-25px; margin-right: 20px; display:block;">
            <a class="btn btn-sm btn-theme02" onclick="create_row()">Add</a>
        </div>
        <div class="col-sm-1" id="button_updatedetail" style="margin-left:-25px; margin-right: 20px; display:none;">
            <a class="btn btn-sm btn-theme02" onclick="update_row()">Update</a>
        </div>
        <div class="col-sm-1">
            <a class="btn btn-sm btn-danger" onclick="clearForm()">Clear</a>
        </div>
    </div>
    <br>
    <div id="table_promo_detail" style="margin-top:30px;">
        <div class="adv-table">
            <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-detail">
                <thead>
                    <th>No</th>
                    <th>Status</th>
                    <th>Features</th>
                    <th>Option</th>
                </thead>
                <tbody id="table-body-detail">
                    @isset($details)
                        @php($i=1)
                        @foreach($details as $key)
                            <input type="hidden" id="detail_id{{ $i }}" value="{{ $key->id }}">
                            <tr style="width:100%" id="trow{{ $i }}" class="trow">
                                <td>{{ $i }}</td>
                                <td>{{ $key->get_status() }}</td>
                                <input type="hidden" name="status[]" id="status{{ $i }}" value="{{ $key->status }}">
                                <td>{{ $key->text }}</td>
                                <input type="hidden" name="text[]" id="text{{ $i }}" value="{{ $key->text }}">
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
    <br>
    <div class="form-group">
        <div class="col-lg-offset-5">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>

<script>
    $(document).ready(function(){
        $(".select2").select2({
            width:'100%',
            templateSelection: formatText,
            templateResult: formatText,
        });
    
        function formatText (icon) {
            return $('<span><i class="fas ' + $(icon.element).data('icon') + '"></i> ' + icon.text + '</span>');
        };
    })

    function edit_row(row){
        $("#table-body-detail tr").each(function(){
            var number = $(this).find('td:eq(0)').text();
            if(number == row){
                var detail_id = $('#detail_id'+row).val();
                var status = $('#status'+row).val();
                var text = $(this).find('td:eq(2)').text();

                $('#status').val(status).change();
                $('#feature').val(text);
                $('#detail_id').val(detail_id);
                $('#row_number').val(number);

                document.getElementById('button_adddetail').style.display = 'none';
                document.getElementById('button_updatedetail').style.display = 'block';
                document.getElementById('title-edit').innerHTML ="<strong>Update Detail Row : "+row+"</strong>";
            }
        });
    }

    function create_row(){
        var token = $("meta[name='csrf-token']").attr("content");
        var status = $('#status').val();
        var feature = $('#feature').val();
        var promo_id = $('#promo_id').val();

        if(feature != ""){
            if(promo_id == undefined){
                var i = $('#table-detail tbody tr.trow').length + 1;
                if(status == 1){
                    get_status = "Included";
                }else{
                    get_status = "Excluded";
                }
                var append = '<tr style="width:100%" id="trow'+i+'" class="trow">'
                append += '<td>'+i+'</td>';
                append += '<td>'+get_status+'</td>';
                append += '<input type="hidden" name="status[]" id="status'+i+'" value="'+status+'">';
                append += '<td>'+feature+'</td>';
                append += '<input type="hidden" name="text[]" id="text'+i+'" value="'+feature+'">';
                append += '<td class="text-center">';
                append += '<a href="javascript:;" type="button" class="btn btn-primary btn-sm" onclick="edit_row('+i+')"> Edit</a> ';
                append += '<a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row('+i+')"> Delete</a></td></tr>';
                $('#table-body-detail').append(append);
                clearForm();
            }else{
                $.ajax({
                    url : "{{ route('contentpromo.store') }}",
                    type : "post",
                    dataType: 'json',
                    data: {
                        "_token" : $('meta[name="csrf-token"]').attr('content'),
                        "promo_id" : promo_id,
                        "status" : status,
                        "feature" : feature,
                    },
                }).done(function (data) {
                    $('#table-body-detail').append(data.append);
                    clearForm();
                }).fail(function (msg) {
                    alert('Gagal menampilkan data, silahkan refresh halaman.');
                });
            }
        }
        
    }

    function update_row(){
        var token = $("meta[name='csrf-token']").attr("content");
        var new_detail_id = $('#detail_id').val();
        var row_number = $('#row_number').val();
        var new_status = $('#status').val();
        var new_feature = $('#feature').val();
        var promo_id = $('#promo_id').val();

        console.log(new_detail_id);
        if(new_feature != ""){
            if(promo_id == undefined){
                $("#table-body-detail tr").each(function(){
                    var number = $(this).find('td:eq(0)').text();
                    if(row_number == number){
                        if(new_status == 1){
                            var text_status = "Included";
                        }else{
                            var text_status = "Excluded";
                        }
                        var text_feature = new_feature;

                        $(this).find('td:eq(1)').text(text_status);
                        $('#status'+number).val(new_status);
                        $(this).find('td:eq(2)').text(text_feature);
                        $('#text'+number).val(text_feature);
                    }
                })
                clearForm();
            }else{
                $.ajax({
                    url : 'contentpromo/'+new_detail_id,
                    type : "put",
                    dataType: 'json',
                    data: {
                        "_token" : $('meta[name="csrf-token"]').attr('content'),
                        "detail_id" : new_detail_id,
                        "status" : new_status,
                        "feature" : new_feature,
                    },
                }).done(function (data) {
                    console.log(data);
                    $("#table-body-detail tr").each(function(){
                        var number = $(this).find('td:eq(0)').text();
                        var detail_id = $('#detail_id'+number).val();
                        if(data.id == detail_id){
                            if(data.status == 1){
                                var text_status = "Included";
                            }else{
                                var text_status = "Excluded";
                            }
                            var text_feature = data.text;

                            $(this).find('td:eq(1)').text(text_status);
                            $(this).find('td:eq(2)').text(text_feature);
                        }
                    })
                    clearForm();
                }).fail(function (msg) {
                    alert('Gagal menampilkan data, silahkan refresh halaman.');
                });
            }
        }
    }

    function delete_row(id){
        var promo_id = $('#promo_id').val();
        console.log(promo_id);

        if(promo_id == undefined){
            $('#trow'+id).remove();
            correctionNumber();
        }else{
            var detail_id = $('#detail_id'+id).val();
            $.ajax({
                url: "contentpromo/"+detail_id,
                type: 'DELETE',
                data: {
                    "_token" : $('meta[name="csrf-token"]').attr('content'),
                    "row_number" : id,
                },
            }).done(function (data) {
                $('#trow'+data).remove();
                correctionNumber();
            }).fail(function (msg) {
                alert('Gagal menampilkan data, silahkan refresh halaman.');
            });
        }

    }

    function clearForm(){
        $('#detail_id').val("");
        $('#row_number').val("");
        $('#feature').val("");
        $('#status').val(1).change();
        document.getElementById('button_adddetail').style.display = 'block';
        document.getElementById('button_updatedetail').style.display = 'none';
        document.getElementById('title-edit').innerHTML ="<strong>Add Package Detail</strong>";
    }

    function correctionNumber(){
        var i = 1;
        $("#table-body-detail tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }

    function getPackage(id){
        $.ajax({
            url : "/api/package/"+id,
            type : "get",
            dataType: 'json',
        }).done(function (data) {
            var price = data.data.price;
            var number_meet = data.data.number_meet;
            var duration_inhour = data.data.duration_inhour;
            var discount_rate = data.data.discount_rate;
            var discount_price = (price - (price / 100 * discount_rate));
            var price_inhour = price / number_meet / duration_inhour;
            var discount_price_inhour = discount_price / number_meet / duration_inhour;
            $('#price').html('<b>Rp '+number_format(price_inhour,0,",",".")+"/jam</b>");
            $('#discount_rate').html('<b>'+number_format(discount_rate, 0, ",", ".")+'%</b');
            $('#discount_price').html('<b>Rp '+number_format(discount_price_inhour, 0, ",", ".")+"/jam</b>");
        }).fail(function (msg) {
            alert('Gagal menampilkan data, silahkan refresh halaman.');
        });
    }
</script>

@isset($data)
<script>
    $(document).ready(function(){
        var package_id = $('#package_id').val();
        if(package_id != null || package_id != "#"){
            getPackage(package_id);
        }
    });
</script>
@endisset