@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>{{__('Список кабинетов')}}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('cabinets.create') }}">{{ __('Добавить кабинет') }}</a>
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
            <th>{{__('Номер')}}</th>
            <th></th>

        </tr>
        @foreach ($cabinets as $cabinet)
            <tr>
                <td>{{ $cabinet->title }}</td>
                <td>{{ $cabinet->number }}</td>
                <td>
                    <a class="btn btn-primary" href="{{ route('cabinets.edit',$cabinet->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['cabinets.destroy', $cabinet->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>

@endsection
