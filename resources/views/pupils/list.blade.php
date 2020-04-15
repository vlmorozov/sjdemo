    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Pupils</h2>
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
            <th>FIO</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($pupilsFromClass as $key => $pupil)
            <tr>
                <td>{{ $pupil->id }}</td>
                <td>{{ $pupil->user->name }}</td>
                <td>
                    {!! Form::open(['method' => 'DELETE','route' => ['classes.destroyPupil', $pupil->schoolClass->id, $pupil->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>



