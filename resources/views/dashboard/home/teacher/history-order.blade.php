<h4 class="mb"><i class="fa fa-angle-right"></i> History Order for {{ $teacher->teacher->name }}</h4>
<div class="adv-table">
    <input type="hidden" id="teacher_id" value="{{ $teacher->id }}">
    <table cellpadding="0" cellspacing="0" width="100%" class="table table-bordered datatable dt-responsive wrap" id="table-detail">
        <thead>
            <th>No</th>
            <th>Order ID</th>
            <th>Student</th>
            <th>Grade</th>
            <th>Course</th>
            <th>Package</th>
            <th>Type</th>
            <th>Schedules</th>
            <th>Status</th>
        </thead>
        <tbody id="table-body-detail">
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#table-detail').DataTable().clear().destroy();
        $('#table-detail').DataTable({
            "responsive": true,
            "processing" : true,
            "serverSide" : true,
            "order": [[ 0, "desc" ]],
            "ajax" : {
                "url": "/api/orderhistory/"+$('#teacher_id').val(),
                "type" : "get",
                "data" : {
                    "_token" : $("meta[name='csrf-token']").attr("content"),
                }
            },"columns" : [
                {data : "no", name : "no", searchable : false},
                {data : "order_id", name : "order_id"},
                {data : "student_name", name : "student_name"},
                {data : "grade_id", name : "grade_id"},
                {data : "course_name", name : "course_name"},
                {data : "package_name", name : "package_name"},
                {data : "order_type", name : "order_type"},
                {data : "schedules", name : "schedules", orderable : false},
                {data : "status", name : "status", orderable : false},                
            ],
            "columnDefs" : [
                {
                    render: function (data, type, full, meta) {
                        return '<strong>'+data+'</strong>';
                    },
                    targets: [1],
                },
                {
                    render: function (data, type, full, meta) {
                        if(data == 'offline'){
                            return '<span style="background: #f96f59; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Offline</span>';
                        }else{
                            return '<span style="background: #008374; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Online</span>';
                        }
                    },
                    targets: [6],
                },
                {
                    render: function (data, type, full, meta) {
                        if(isNaN(data)){
                            return data;                            
                        }else{
                            if(data == -1){
                                return '<span style="background: #d9534f; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Declined</span>';
                            }else if(data == 1){
                                return '<span style="background: #5cb85c; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Ongoing</span>';
                            }else if(data == 2){
                                return '<span style="background: #000000; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Finish</span>';
                            }else{
                                return '<span style="background: #f0ad4e; color:white; border-radius: 3px; padding: 0 10px 0 10px;">Not Confirmed</span>';
                            }
                        }
                    },
                    targets: [8],
                }
            ],
            oLanguage : {sProcessing: "<div id='loader'></div>"},
        });
    });
</script>