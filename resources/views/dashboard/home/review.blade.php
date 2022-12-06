<form class="form-horizontal style-form" method="post" action="{{ route('orderreview.store') }}">
    @csrf
    <h4 class="mb"><i class="fa fa-angle-right"></i> Review for Order ID : <strong>#{{ $data->order_id }}</strong></h4>
    <input type="hidden" name="order_id" id="order_id" value="{{ $data->id }}">
    <input type="hidden" name="teacher_id" id="teacher_id" value="{{ $data->teacher_id }}">
    <div class="row">
        <div class="col-sm-5">
            <img src="{{ asset($teacher->photo) }}" class="img-fluid" style="object-fit:cover; min-height: 250px; min-width: 100px; max-height:250px;" alt="">
            <h4 class="text-center">{{ $teacher->name }}</h4>
        </div>
        <div class="col-sm-7">
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">Rating</label>
                {{-- <div class="col-sm-9">
                    <input type="text" class="form-control" name="rating" id="rating" value="">
                </div> --}}
                <div class="col-sm-10">
                    <div class="col-md-2">
                        <div class="radio">
                            <label><input type="radio" name="optionsRadios" id="options1" value="1">1</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="radio">
                            <label><input type="radio" name="optionsRadios" id="options2" value="2">2</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="radio">
                            <label><input type="radio" name="optionsRadios" id="options3" value="3">3</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="radio">
                            <label><input type="radio" name="optionsRadios" id="options4" value="4">4</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="radio">
                            <label><input type="radio" name="optionsRadios" id="options5" value="5">5</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 col-sm-2 control-label">Review</label>
                <div class="col-sm-10">
                    <textarea type="text" class="form-control" name="review" id="review" placeholder="Write your Review"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="text-right m-20">
                    <button class="btn btn-theme" type="submit"><i class="fa fa-save"></i> Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>