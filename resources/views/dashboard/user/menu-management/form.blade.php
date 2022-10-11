<h4 class="mb"><i class="fa fa-angle-right"></i> Access Menu for Role {{ $data->name }}</h4>
<form class="form-horizontal style-form" method="post" action="{{ route('menumapping.update', ['id' => $data->id]) }}">
    {{ method_field('PUT') }}
    @csrf
    @foreach($moduls as $modul)
    <div class="form-group">
        <label class="col-sm-3 col-sm-3 control-label">{{ $modul->modul_name }}</label>
    </div>
    <div class="form-group">
        @foreach($modul->submoduls as $submodul)
            <div class="col-md-3">
                <label class="checkbox-inline"><input type="checkbox" name="checkBoxes" id="options-{{ $submodul->submapping_id }}" value="{{ $submodul->submapping_id }}" @if(array_search($submodul->submapping_id, $submappings)) checked @endif> {{ $submodul->jenis_id }} </label>
            </div>
        @endforeach
    </div>
    @endforeach
    <div class="form-group">
        <div class="col-lg-offset-3 col-lg-9">
            <button class="btn btn-theme" type="submit">@isset($data) Update @else Submit @endisset</button>
        </div>
    </div>
</form>