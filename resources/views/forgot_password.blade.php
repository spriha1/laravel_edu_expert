@extends('layouts.header')
@section('title', 'Edu Expert')
@section('content')
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                Edu<b>Xpert</b>
            </div>
            <div class="login-box-body">
                <p class="login-box-msg">Reset Password</p>
                {{ Form::open(['url' => '/forgot_password', 'id' => 'login', 'name' => 'login']) }}
                    {{ session()->get('reset_msg') }}
                    <div id="alert" class='alert alert-danger' style="display: none;">
                    </div>
                    <p style="color : #ff0000"></p>
                    <div class="form-group has-feedback">
                        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username', 'id' => 'username']) }}
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-8">
                            <div class="checkbox icheck">
                                <label>
                                    <a href="/">Sign In</a>
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            {{ Form::submit('Reset', ['class' =>'btn btn-primary btn-block btn-flat']) }}
                        </div>
                    </div>

                {{ Form::close() }}
            </div>
        </div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="forgot_password_footer" src="{{ mix('/js/footer.js') }}"></script>
@endsection