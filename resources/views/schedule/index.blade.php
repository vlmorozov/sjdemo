@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Schedule Management</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('schedule.create') }}"> Create Schedule</a>
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
            <th>{{ __('Класс') }}</th>
            <th>{{ __('Период') }}</th>
        </tr>
        @foreach ($schedules as $key => $schedule)
            <tr>
                <td>{{ $schedule->schoolClass->title }}</td>
                <td>{{ $schedule->dateFrom  }} - {{ $schedule->dateTo  }}</td>
                <td>
                    <a class="btn btn-info" href="{{ route('schedule.show',$schedule->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('schedule.edit',$schedule->id) }}">Edit</a>
                    {!! Form::open(['method' => 'DELETE','route' => ['schedule.destroy', $schedule->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
    </table>

@endsection
