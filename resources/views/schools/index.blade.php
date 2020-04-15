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

            @foreach ($columns as $column)
                @if ($column['sort'])
                    <th {{ isset($column['width'])? "width = '".$column['width']."'" :""  }}>
                        <a href="{{ route('schools.index')}}?&sort={{$column['field']}}&direction={{ ($column['field'] == $sort && $direction == 'asc') ? "desc" : "asc" }}">{{ $column['title'] }}
                            @if ($column['field'] == $sort)
                                {{($direction == 'asc') ? "Возр" : "Убыв" }}
                            @endif
                        </a>
                    </th>
                @else
                    <th>{{$column['title']}}</th>
                @endif
            @endforeach

        </tr>
        @foreach ($data as $key => $school)
            <tr>
                <td>{{ $school->id }}</td>
                <td>{{ $school->sysname }}</td>
                <td>{{ $school->title }}</td>
                <td>{{ $school->owner->name }}</td>
                <td>{{ $school->address }}</td>
                <td>{{ $school->phone }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('schools.show',$school->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('schools.edit',$school->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['schools.destroy', $school->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>


    {!! $data->render() !!}


@endsection
