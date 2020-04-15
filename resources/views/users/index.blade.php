@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Users Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
            </div>
        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    {!! $formFilters->render() !!}

    <table class="table table-bordered">
        <tr>
            @foreach ($columns as $column)
                @if ($column['sort'])
                    <th {{ isset($column['width'])? "width = '".$column['width']."'" :""  }}>
                        <a href="{{ route('users.index')}}?{{http_build_query($formFilters->getValues())}}&sort={{$column['field']}}&direction={{ ($column['field'] == $sort && $direction == 'asc') ? "desc" : "asc" }}">{{ $column['title'] }}
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
        @foreach ($data as $key => $user)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>

                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                            <label class="badge badge-success">{{ $v }}</label>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>


    {!! $data->appends(array_merge($formFilters->getValues(), ['sort' => $sort, 'direction' => $direction]))->render() !!}


@endsection
