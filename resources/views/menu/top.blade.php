<!-- Left Side Of Navbar -->
<ul class="navbar-nav mr-auto">
@guest
@else
    @if (count(Auth::user()->schools))
    <li>{{ Auth::user()->currentSchool()->title }}</li>
    @endif

    @if (Auth::user()->can('user-list'))
    <li><a class="nav-link" href="{{ route('users.index') }}">Manage Users</a></li>
    @endif
    @if (Auth::user()->can('role-list'))
    <li><a class="nav-link" href="{{ route('roles.index') }}">Manage Role</a></li>
    @endif
    @if (Auth::user()->can('permission-list'))
    <li><a class="nav-link" href="{{ route('permissions.index') }}">Manage Permission</a></li>
    @endif
    @if (Auth::user()->can('school-list'))
    <li><a class="nav-link" href="{{ route('schools.index') }}">Manage Schools</a></li>
    @endif
    @if (Auth::user()->can('class-list'))
        <li><a class="nav-link" href="{{ route('classes.index') }}">{{ __('Список классов') }}</a></li>
    @endif
    @if (Auth::user()->can('subject-list'))
        <li><a class="nav-link" href="{{ route('subjects.index') }}">{{ __('Список предметов') }}</a></li>
    @endif
    @if (Auth::user()->can('cabinet-view'))
        <li><a class="nav-link" href="{{ route('cabinets.index') }}">{{ __('Список кабинетов') }}</a></li>
    @endif
    @if (Auth::user()->can('teacher-list'))
        <li><a class="nav-link" href="{{ route('teachers.index') }}">{{ __('Список учителей') }}</a></li>
    @endif
    @if (Auth::user()->can('schedule-view'))
        <li><a class="nav-link" href="{{ route('schedule.index') }}">{{ __('Расписание') }}</a></li>
    @endif

@endguest
</ul>
