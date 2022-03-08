@if(auth()->user()->UserRole->Role === 'Administrator')

    @include('layouts.adminmenu')

@elseif(\Auth::guard('web')->User()->UserRole->Role === 'IT Support')

    @include('layouts.itsupportmenu')

@elseif(auth()->user()->UserRole->ID == '9')

    @include('layouts.deptkhimenu')

@elseif(auth()->user()->UserRole->ID == '11')

    @include('layouts.deptdiklat')

@else

    @include('layouts.karyawanmenu')

@endif