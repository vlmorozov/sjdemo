@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('Список учителей')}}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('teachers.create') }}">{{ __('Добавить учителя') }}</a>
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

            <th>{{__('Учитель')}}</th>
            <th>{{__('Предметы')}}</th>
            <th></th>

        </tr>
        @foreach ($teachers as $teacher)
            <tr>
                <td>{{ $teacher->user->name }}</td>
                <td>
                    @foreach($teacher->subjects as $subject)
                        {{$subject->title}}<br />
                    @endforeach
                </td>
                <td>
                    <a class="btn btn-primary" href="{{ route('teachers.edit',$teacher->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['teachers.destroy', $teacher->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>

@endsection
