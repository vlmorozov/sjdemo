@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Schools Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('schools.create') }}"> Create New School</a>
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
            <th>Sysname</th>
            <th>Title</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($data as $key => $school)
            <tr>
                <td>{{ $school->id }}</td>
                <td>{{ $school->name }}</td>
                <td>{{ $school->email }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('school.show',$school->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('school.edit',$school->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['schools.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>


    {!! $data->render() !!}


@endsection
