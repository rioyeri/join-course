<style>
    .form-group-sm .form-control{
        margin-bottom : 5px;
    }

    .select2-container {
        margin-bottom : 5px;
    }
</style>

<h4 class="mb"><i class="fa fa-angle-right"></i> Update Content : {{ $data->segment }}</h4>
<form class="form-horizontal style-form" method="post" action="{{ route('contentmanagement.update', ['id' => $data->id]) }}" enctype="multipart/form-data">
    {{ method_field('PUT') }}
    @csrf
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Title</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" name="title" id="title" value="@isset($data->title){{ $data->title }}@endisset">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Subtitle</label>
        <div class="col-sm-9">
            <textarea class="form-control" name="subtitle" id="subtitle" rows="3">@isset($data->subtitle){{ $data->subtitle }}@endisset</textarea>
        </div>
    </div>
    <input type="hidden" id="content_id" value="{{ $data->id }}">
    @if($data->id != 1)
    <div id="update_box" style="display: none;">
        <h5 class="mb" id="title-edit"><strong>Update Detail Row : </strong></h5>
        <input type="hidden" id="detail_id" value="0">
        <div class="form-group">
            @if($data->id == 3)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <select class="form-control select2" name="detail_title" id="detail_title" onchange="getTeachersDetailbyName(this.value)">
                            <option value="#" selected disabled>-- Choose --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->teacher->name }}">{{ $teacher->teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @else
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Title</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_title" id="detail_title" value="">
                    </div>
                </div>
            @endif
            @if($data->id != 2 && $data->id != 6 && $data->id != 3 && $data->id != 8 && $data->id != 9)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_subtitle" id="detail_subtitle" value="">
                    </div>
                </div>
            @elseif($data->id == 3)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Subtitle</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_subtitle" id="detail_subtitle" value="" readonly>
                    </div>
                </div>
            @endif
            @if($data->id == 3)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="detail_description" id="detail_description" rows="3" readonly></textarea>
                    </div>
                </div>
            @else
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Description</label>
                    <div class="col-sm-9">
                        <textarea class="form-control" name="detail_description" id="detail_description" rows="3"></textarea>
                    </div>
                </div>
            @endif
            @if($data->id != 6 && $data->id != 2 && $data->id != 9)
                <div class="form-group-sm">
                    <label class="control-label col-sm-3 col-sm-3">Image</label>
                    <div class="col-md-9">
                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
                                <img src="" alt="" id="image-display"/>
                            </div>
                            <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;">
                            </div>
                            @if($data->id != 3)
                                <div>
                                    <span class="btn btn-theme02 btn-file">
                                        <span class="fileupload-new"><i class="fa fa-paperclip"></i> Select image</span>
                                        <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                        <input type="file" class="default" name="detail_image" id="detail_image"/>
                                    </span>
                                    <a href="javascript:;" class="btn btn-theme04 fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> Remove</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @elseif($data->id == 2)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Icon</label>
                    <div class="col-sm-9">
                        <select class="form-control select2" name="detail_image" id="detail_image">
                            <option value="#" selected disabled>-- Choose --</option>
                            @foreach ($icons as $icon)
                                <option value="{{ $icon->icon }}" data-icon="{{ $icon->icon }}"><i class="{{ $icon->icon }}"></i>{{$icon->icon}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif
            @if($data->id != 3 && $data->id != 4 && $data->id != 6 && $data->id != 8 && $data->id != 9)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Link</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_link" id="detail_link" value="">
                    </div>
                </div>
            @endif
            @if($data->id != 3 && $data->id != 4 && $data->id != 6 && $data->id != 8 && $data->id != 9)
                <div class="form-group-sm">
                    <label class="col-sm-3 col-sm-3 control-label">Link Text</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="detail_linktext" id="detail_linktext" value="">
                    </div>
                </div>
            @endif
            
            <div class="form-group-sm">
                <div class="col-lg-offset-3 col-lg-9">
                    <a class="btn btn-theme03" onclick="update_row()">Update Detail</a>
                </div>
            </div>
        </div>
    </div>
    @if($data->id != 7)
    <div class="adv-table">
        <table cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-detail">
            <thead>
                <th>No</th>
                <th>Title</th>
                <th @if($data->id == 2 || $data->id == 6 || $data->id == 8 || $data->id == 9) style="display:none;" @endif>Subtitle</th>
                <th>Description</th>
                <th @if($data->id == 6 || $data->id == 9) style="display:none;" @endif>@if($data->id == 2) Icon @else Image @endif</th>
                <th @if($data->id == 3 || $data->id == 4 || $data->id == 6 || $data->id == 8 || $data->id == 9) style="display:none;" @endif>Link</th>
                <th @if($data->id == 3 || $data->id == 4 || $data->id == 6 || $data->id == 8 || $data->id == 9) style="display:none;" @endif>Link Text</th>
                <th>Options</th>
            </thead>
            <tbody id="table-body-detail">
                @php($i=1)
                @foreach($details as $key)
                    <input type="hidden" id="detail_id{{ $i }}" value="{{ $key->id }}">
                    <tr style="width:100%" id="trow{{ $i }}" class="trow" role="row">
                        <td>{{ $i }}</td>
                        <td>{{ $key->title }}</td>
                        <td @if($data->id == 2 || $data->id == 6 || $data->id == 8 || $data->id == 9) style="display:none;" @endif>{{ $key->subtitle }}</td>
                        <td>{{ $key->description }}</td>
                        <td @if($data->id == 6 || $data->id == 9) style="display:none;" @endif>
                            @if($key->image != "")
                                @if($data->id == 2)
                                    <input type="hidden" id="image_name{{ $i }}" value="{{ $key->image }}">
                                    <i class="{{ $key->image }}" id="icon{{ $i }}"></i>
                                @elseif($data->id == 3)
                                    @php($path = 'dashboard/assets/users/photos/')
                                    <input type="hidden" id="image_name{{ $i }}" value="{{ asset($path.$key->image) }}">
                                    <img id="image{{ $i }}" src="{{ asset($path.$key->image) }}" style="object-fit:cover; min-height: 50px; max-height:50px;">
                                @elseif($data->id == 8)
                                    @php($path = 'landingpage/assets/img/'.$key->image)
                                    <input type="hidden" id="image_name{{ $i }}" value="{{ asset($path) }}">
                                    <img id="image{{ $i }}" src="{{ asset($path) }}" style="object-fit:cover; min-height: 50px; max-height:50px;">
                                @else
                                    @php($path = 'landingpage/assets/img/testimonials/')
                                    <input type="hidden" id="image_name{{ $i }}" value="{{ asset($path.$key->image) }}">
                                    <img id="image{{ $i }}" src="{{ asset($path.$key->image) }}" style="object-fit:cover; min-height: 50px; max-height:50px;">
                                @endif
                            @else
                                NOT SET YET
                            @endif
                        </td>
                        <td @if($data->id == 3 || $data->id == 4 || $data->id == 6 || $data->id == 8 || $data->id == 9) style="display:none;" @endif>{{ $key->link }}</td>
                        <td @if($data->id == 3 || $data->id == 4 || $data->id == 6 || $data->id == 8 || $data->id == 9) style="display:none;" @endif>{{ $key->link_text }}</td>
                        <td class="text-center"><a href="javascript:;" type="button" class="btn btn-primary btn-trans waves-effect w-xs waves-danger m-b-5" onclick="edit_row({{ $i }})">Edit</a></td>
                    </tr>
                    @php($i++)
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endif
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">Update</button>
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
    var content_id = $('#content_id').val();
    $("#table-body-detail tr").each(function(){
        var number = $(this).find('td:eq(0)').text();
        if(number == row){
            var title = $(this).find('td:eq(1)').text();
            var subtitle = $(this).find('td:eq(2)').text();
            var description = $(this).find('td:eq(3)').text();
            var image = $('#image_name'+row).val();
            var link = $(this).find('td:eq(5)').text();
            var link_text = $(this).find('td:eq(6)').text();
            var detail_id = $('#detail_id'+row).val();

            if(content_id == 3){
                $('#detail_title').val(title).change();
            }else{
                $('#detail_title').val(title);
            }
            $('#detail_subtitle').val(subtitle);
            $('#detail_description').val(description);
            $('#detail_link').val(link);
            $('#detail_linktext').val(link_text);
            console.log(content_id);
            if(content_id != 6 && content_id != 9){
                if(content_id == 2){
                    $('#detail_image').val(image).change();
                }else{
                    console.log(image);
                    if(image != undefined){
                        document.getElementById('image-display').src = image;
                    }else{
                        document.getElementById('image-display').src = "";
                    }
                }
            }

            $('#detail_id').val(detail_id);
            document.getElementById('title-edit').innerHTML = "<strong>Update Detail Row : "+row+"<strong>";
            document.getElementById('update_box').style.display = 'block';
        }
    });
}

function update_row(){
    var token = $("meta[name='csrf-token']").attr("content");
    var content_id = $('#content_id').val();
    var detail_id = $('#detail_id').val();

    var title = $('#detail_title').val();

    if(content_id == 2 || content_id == 6 || content_id == 8 || content_id == 9){
        var subtitle = "";
    }else{
        var subtitle = $('#detail_subtitle').val();
    }

    var description = $('#detail_description').val();

    if(content_id != 2 && content_id != 3 && content_id != 6 && content_id != 9){
        var selector = document.getElementById('detail_image');
        var image = selector.files[0];
        if(image == undefined){
            image = "";
        }
    }else if(content_id == 2){
        var image = $('#detail_image').val();
    }else{
        var image = "";
    }

    if(content_id == 4 || content_id == 6 || content_id == 8 || content_id == 9){
        var link = "";    
    }else{
        var link = $('#detail_link').val();
    }

    if(content_id == 3 || content_id == 4 || content_id == 6 || content_id == 8 || content_id == 9){
        var link_text = "";
    }else{
        var link_text = $('#detail_linktext').val();
    }
    var form_data = new FormData();
    form_data.append('detail_id', detail_id);
    form_data.append('content_id', content_id);
    form_data.append('title', title);
    form_data.append('subtitle', subtitle);
    form_data.append('description', description);
    form_data.append('image', image);
    form_data.append('link', link);
    form_data.append('link_text', link_text);
    console.log(form_data);
    $.ajax({
        url : "{{ route('contentmanagement.store') }}",
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
        $("#table-body-detail tr").each(function(){
            var number = $(this).find('td:eq(0)').text();
            var detail_id = $('#detail_id'+number).val();
            if(data.id == detail_id){
                var new_title = data.title;
                var new_subtitle = data.subtitle;
                var new_description = data.description;
                if(data.link_text == null){
                    var new_link = "";
                    var new_link_text = "";
                }else{
                    var new_link = data.link;
                    var new_link_text = data.link_text;
                }

                $(this).find('td:eq(1)').text(new_title);
                $(this).find('td:eq(2)').text(new_subtitle);
                $(this).find('td:eq(3)').text(new_description);
                $(this).find('td:eq(5)').text(new_link);
                $(this).find('td:eq(6)').text(new_link_text);

                if(content_id == 2){
                    $('#icon'+number).attr("class", "");
                    // document.getElementById('icon'+number).classList.add(data.image);
                    document.getElementById('icon'+number).setAttribute("class", data.image);
                    $('#image_name'+number).val(data.image);
                }else{
                    if(data.image != "" && data.image != null){
                        if(content_id == 3){
                            image_path = 'dashboard/assets/users/photos/';
                        }else if(content_id == 8){
                            image_path = 'landingpage/assets/img/';
                        }else{
                            image_path = 'landingpage/assets/img/testimonials/';
                        }
                        field_image = $(this).find('td:eq(4)').text().replaceAll(/\s/g, '');
                        
                        if(field_image == "NOTSETYET"){
                            var content1 = '<input type="hidden" id="image_name'+number+'" value="">';
                            var content2 = '<img id="image'+number+'" src="" style="object-fit:cover; min-height: 50px; max-height:50px;">';
                            content = content1.concat(content2);
                            console.log(content);
                            $(this).find('td:eq(4)').html(content);
                        }

                        document.getElementById('image'+number).src = image_path+data.image;
                        $('#image_name'+number).val(image_path+data.image);
                    }
                }
            }
        })
        $('#detail_id').val("");
        document.getElementById('update_box').style.display = 'none';
    }).fail(function (msg) {
        alert('Gagal menampilkan data, silahkan refresh halaman.');
    });
}

function getTeachersDetailbyName(name){
    $.ajax({
        url : "{{ route('getTeachersDetailbyName') }}",
        type : "get",
        dataType: 'json',
        data: {
            "name" : name,
        }
    }).done(function (data) {
        console.log(data);
        $('#detail_subtitle').val(data.title);
        $('#detail_description').val(data.description);
        if(data.image != null){
            console.log(data.image);
            image_path = 'dashboard/assets/users/photos/';
            document.getElementById('image-display').src = image_path+data.image;
        }else{
            console.log("else", data.image);
            document.getElementById('image-display').src = "";
        }
    }).fail(function (msg) {
        alert('Gagal menampilkan data, silahkan refresh halaman.');
    });
}
</script>