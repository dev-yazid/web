@extends('layouts.app')

<!-- Main Content -->
@section('content')
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="wrapper-lg" role="form" method="POST" action="{{ url('/admin/password/email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">E-Mail Address</label>

                                <input type="email" class="form-control input-lg" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Send Password Reset Link</button>

                    </form>
                </div>
@endsection