@extends('layouts.app')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Schedule</h2>
            </div>

        </div>
    </div>


    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <div class="container">

        <Schedule
            @if (isset($scheduleData)):data = '{{ json_encode($scheduleData) }}'@endif
            @if (isset($subjects)):subjects = '{{ json_encode($subjects) }}'@endif
            @if (isset($teachers)):teachers = '{{ json_encode($teachers) }}'@endif
            @if (isset($cabinets)):cabinets = '{{ json_encode($cabinets) }}'@endif
            @if (isset($schoolClasses)):schoolclasses = '{{ json_encode($schoolClasses) }}'@endif
        />
</div>

@endsection
