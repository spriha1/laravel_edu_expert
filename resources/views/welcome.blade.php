@extends('layouts.header')
@section('title', 'Edu Expert')
@section('content')
    <body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            Edu<b>Xpert</b>
        </div>
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start</p>

            {{ Form::open(['url' => 'login', 'id' => 'login', 'name' => 'login']) }}

                @if ($error = $errors->first('password'))
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endif

                {{ session()->get('login_msg') }}

                <div id="alert" class='alert alert-danger' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    {{ Form::text('username', old('username'), ['class' => 'form-control', 'placeholder' => 'Username', 'id' => 'username']) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password']) }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <a href="forgot_password">Forgot Password?</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        {{ Form::submit('Sign in', ['class' =>'btn btn-primary btn-block btn-flat']) }}
                    </div>
                </div>

            {{ Form::close() }}
            
            <a href="register" class="text-center">Register Here</a>
            <br>
            <button class="btn btn-light btn-block btn-flat">
                <a href="/login/google" class="text-center">
                    <img src="/images/google.png">
                    Sign In with Google
                </a>
            </button>
            <button class="btn btn-light btn-block btn-flat">
                <a href="/login/facebook" class="text-center">
                    <img src="/images/facebook.png">
                    Sign In with Facebook
                </a>
            </button>
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="footer" src="{{ mix('/js/footer.js') }}"></script>
@endsection