<script>
    $(".select2").select2({
        width:'100%',
    });
</script>
<h4 class="mb"><i class="fa fa-angle-right"></i> Teacher's Package : {{ $data->teacher->name }}</h4>
<form class="form-horizontal style-form" method="post" action="{{ route('setTeacherPrice', ['id' => $data->id]) }}">
    {{ method_field('PUT') }}
    @csrf
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Package</label>
        <div class="col-sm-9">
            <select class="form-control select2" parsley-trigger="change" id="package" >
                <option value="#" disabled selected>-- Choose --</option>
                @foreach ($packages as $package)
                    <option value="{{$package->id}}" >{{$package->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">Price</label>
        <div class="col-sm-9">
            <input type="number" class="form-control" id="price" value="0">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
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
        <div class="form-group">
            <div class="text-right m-20">
                <button class="btn btn-theme" type="submit"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</form>