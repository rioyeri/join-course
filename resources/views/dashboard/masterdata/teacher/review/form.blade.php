<h4 class="mb"><i class="fa fa-angle-right"></i> Reviews</h4>
<br>
<div class="adv-table">
    <table width="100%" cellpadding="0" cellspacing="0" class="table table-bordered datatable dt-responsive wrap" id="table-review">
        <thead>
            <th>No</th>
            <th>Student</th>
            <th>Rating</th>
            <th>Review</th>
            {{-- <th>Option</th> --}}
        </thead>
        <tbody id="table-body-detail">
            @isset($details)
                @php($i=1)
                @foreach($details as $key)
                    <input type="hidden" id="review_id{{ $i }}" value="{{ $key->id }}">
                    <tr style="width:100%" id="trow{{ $i }}" class="trow">
                        <td>{{ $i }}</td>
                        <td>{{ $key->get_order->get_student->student->name }}</td>
                        <td>{{ $key->rating }}</td>
                        <td>{{ $key->review }}</td>
                        {{-- <td class="text-center">
                            <a href="javascript:;" type="button" class="btn btn-danger btn-sm" onclick="delete_row({{ $i }})">Delete</a>
                        </td> --}}
                    </tr>
                    @php($i++)
                @endforeach
            @endisset
        </tbody>
    </table>
</div>

<script>
    $(document).ready(function(){
        $('#table-review').DataTable();
    });
    function delete_row(id){
        $('#trow'+id).remove();
        correctionNumber();
    }

    function correctionNumber(){
        var i = 1;
        $("#table-body-detail tr").each(function(){
            $(this).find('td:eq(0)').text(i++);
        })
    }
</script>