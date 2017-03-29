@extends('layouts.app')
@section('content')
<form role="form" action="{{ url('/admin/login') }}" method="post" class="panel-body wrapper-lg">
   {!! csrf_field() !!}
   <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="control-label">Email <em style="color:#D9534F">*</em></label>
        <input  name="email"  type="email" placeholder="test@example.com" class="form-control input-lg" value="{{ old('email') }}" />
        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>
    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="control-label">Password <em style="color:#D9534F">*</em></label>
            <input name="password" type="password" id="inputPassword" placeholder="Password" class="form-control input-lg">
        @if ($errors->has('password'))
        <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
    </div>
    <!-- <a href="{{ url('/admin/password/reset') }}" class="pull-right m-t-xs"><small>Forgot password?</small></a> -->
    <button type="submit" class="btn btn-primary">Sign in</button>
    <div class="line line-dashed"></div>
</form>
@endsection
