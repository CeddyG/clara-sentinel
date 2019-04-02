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
        <p class="login-box-msg">{{ __('clara-sentinel::passwords.reset_password_mail') }}</p>

        {!! Form::open(['url' => route('password.email'), 'method' => 'post', 'class' => 'panel']) !!}

            <div class="form-group {!! $errors->has('fail') ? 'has-error' : '' !!}">
                {!! $errors->first('fail', '<small class="help-block">:message</small>') !!}
            </div>

            <div class="form-group has-feedback">
              <input name="email" type="email" class="form-control" placeholder="Email">
              <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              {!! $errors->first('email', '<small class="help-block">:message</small>') !!}
            </div>

            <div class="row">
                <div class="col-xs-4">
                  <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('clara::general.send') }}</button>
                </div>
                <!-- /.col -->
            </div>

        {!! Form::close() !!}

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
@endsection
