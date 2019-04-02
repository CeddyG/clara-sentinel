@extends('admin.dashboard')

@section('content')

<div class="login-box">
    <div class="login-logo">
        <h1>{{ __('clara-sentinel::passwords.reset_password') }}</h1>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">{{ __('clara-sentinel::passwords.reset_password') }}</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="code" value="{{ $sCode }}">
            
            <div class="form-group {!! $errors->has('fail') ? 'has-error' : '' !!}">
                {!! $errors->first('fail', '<small class="help-block">:message</small>') !!}
            </div>
            
            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                {!! $errors->first('password', '<small class="help-block">:message</small>') !!}
            </div>
            
            <div class="form-group has-feedback">
                <input name="password_confirmation" type="password" class="form-control" placeholder="Confirme Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('clara::general.send') }}</button>
                </div>
                <!-- /.col -->
            </div>

        </form>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

@endsection
