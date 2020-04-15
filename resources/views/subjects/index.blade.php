@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('Список предметов')}}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('subjects.create') }}">{{ __('Добавить предмет') }}</a>
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

            <th>{{__('Название')}}</th>
            <th></th>

        </tr>
        @foreach ($subjects as $subject)
            <tr>
                <td>{{ $subject->title }}</td>
                <td>
                    {!! Form::open(['method' => 'DELETE','route' => ['subjects.destroy', $subject->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>

@endsection
