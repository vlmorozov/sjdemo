    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Classes of Schools Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('classes.create') }}"> Create New Class School</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Number</th>
            <th>Letter</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($data as $key => $classOfSchool)
            <tr>
                <td>{{ $classOfSchool->id }}</td>
                <td>{{ $classOfSchool->title }}</td>
                <td>{{ $classOfSchool->number }}</td>
                <td>{{ $classOfSchool->letter }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('classes.show', $classOfSchool->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('classes.edit', $classOfSchool->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['classes.destroy', $classOfSchool->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>



