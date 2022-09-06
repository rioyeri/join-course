<div id="load" class="table-responsive">
    <table class="table m-b-0">
        <thead>
            <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Created_at</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $data1)
            <tr>
                <td>{{$data1['no']}}</td>
                <td>{{$data1['ktp']}}</td>
                <td>{{$data1['nama']}}</td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
    {{ $data2 }} 
</div>