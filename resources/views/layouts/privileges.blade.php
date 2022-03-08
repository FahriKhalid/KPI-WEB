<form method="post" action="{{route('privilege',\Auth::guard('web')->User()->ID)}}">
    {{csrf_field()}}
    <input type="hidden" name="_method" value="put">
    <select class="form-control select-switch privilege-select" name="IDRole" onchange="{ this.form.submit(); }">
        @foreach(\Auth::guard('web')->User()->Roles as $role)
            <option value="{{$role->ID}}" {{ auth()->user()->IDRole===$role->ID ? 'selected="selected"' : '' }}>{{$role->Role}}</option>
        @endforeach
    </select>
</form>