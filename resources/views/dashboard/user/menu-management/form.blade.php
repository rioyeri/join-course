<h4 class="mb"><i class="fa fa-angle-right"></i> Access Menu for Role {{ $data->name }}</h4>
<form class="form-horizontal style-form" method="post" action="{{ route('menumapping.update', ['id' => $data->id]) }}">
    {{ method_field('PUT') }}
    @csrf
    @foreach($moduls as $modul)
    <div class="form-group">
        <h4 class="col-sm-3 col-sm-3 control-label"><strong>{{ $modul->modul_name }}</strong></h4>
    </div>
    <div class="form-group">
        @foreach($modul->submoduls as $submodul)
            <div class="row">
                <h5 class="col-sm-3 text-right"><strong>{{ $submodul->submodul_name }}</strong></h5>
                <div class="col-sm-9 mb-15">
                    @foreach ($submodul->submappings as $submapping)
                        <div class="col-md-4">
                            {{-- <label class="checkbox-inline"><input type="checkbox" name="checkBoxes" id="options-{{ $submapping->submapping_id }}" value="{{ $submapping->submapping_id }}" @if(array_search($submapping->submapping_id, $submappings)) checked @endif> {{ $submapping->jenis_id }} </label> --}}
                            <label class="checkbox-inline"><input type="checkbox" name="checkBoxes[]" id="options-{{ $submapping->submapping_id }}" value="{{ $submapping->submapping_id }}" @if(in_array($submapping->submapping_id, $submappings)) checked @endif> {{ $submapping->jenis_id }} </label>
                        </div>
                    @endforeach
                </div>
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