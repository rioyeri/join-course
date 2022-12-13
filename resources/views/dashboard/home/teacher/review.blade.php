<h4 class="mb"><i class="fa fa-angle-right"></i> Reviews for {{ $teacher->teacher->name }}</h4>
<div class="adv-table">
    <input type="hidden" id="teacher_id" value="{{ $teacher->id }}">
    <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered datatable dt-responsive wrap" id="table-detail">
        <thead>
            <th>No</th>
            <th>Reviews</th>
        </thead>
        <tbody id="table-body-detail">
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table-detail').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url": "/api/orderreview/"+$('#teacher_id').val(),
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                }
            },"columns" : [
                {data : "no", name : "no", searchable : false},
                {data : "review", name : "review"},
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });
    });
</script>