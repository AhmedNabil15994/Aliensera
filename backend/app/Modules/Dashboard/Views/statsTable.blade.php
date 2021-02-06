
<div class="x_content x_content_table">
    <table id="tableList" class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>Student ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $value)
            <tr id="tableRaw{{ $value->id }}">
                {{-- {{ dd($value) }} --}}
                <td>{{ $value->id }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
                <td>{{ $value->phone }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{{-- @include('Partials.pagination') --}}
<div class="clearfix"></div>