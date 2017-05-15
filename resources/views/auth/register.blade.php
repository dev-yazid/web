@extends('layouts.app')

@section('content')

<form class="panel-body wrapper-lg" role="form" method="POST" action="{{ url('/admin/register') }}">
    {!! csrf_field() !!}

    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
            <label class="control-label">First Name</label>
            <input type="text" class="form-control input-lg" name="firstname" value="{{ old('firstname') }}">
            @if ($errors->has('firstname'))
            <span class="help-block">
                    <strong>{{ $errors->first('firstname') }}</strong>
            </span>
            @endif
    </div>
    
    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
            <label class="control-label">Last Name</label>
            <input type="text" class="form-control input-lg" name="lastname" value="{{ old('lastname') }}">

            @if ($errors->has('lastname'))
                <span class="help-block">
                    <strong>{{ $errors->first('lastname') }}</strong>
                </span>
            @endif
    </div>
    
    <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
        <label class="control-label">Country</label>
        
        <select name="country" class="form-control m-b">

            @foreach(App\Http\Utilities\Country::all() as $country)
              <option value="{{ $country }}" {{ $flyers->country == $country ? "selected" : "" }}>{{ $country }}</option>
            @endforeach

        </select>
        
        <input type="text" class="form-control input-lg" name="country" value="{{ old('country') }}">

        @if ($errors->has('country'))
            <span class="help-block">
                <strong>{{ $errors->first('country') }}</strong>
            </span>
        @endif
    </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">E-Mail Address</label>
                                <input type="email" class="form-control input-lg" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="control-label">Password</label>
                                <input type="password" class="form-control input-lg" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label class="control-label">Confirm Password</label>

                                <input type="password" class="form-control input-lg" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Sign up</button>
                    </form>
@endsection