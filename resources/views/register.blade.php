@extends('layouts.header')
@section('title', 'Edu Expert')
@section('content')
<style>
    .my-error {
        display: none;
    }
</style>
    <body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            Edu<b>Xpert</b>
        </div>
        <div class="register-box-body">
            <p class="login-box-msg">Create Account</p>

            {{ Form::open(['id' => 'registration', 'name' => 'registration']) }}
                <div id="alert" class='alert alert-danger' style="display: none;">
                </div>
                <img src="/images/load.gif" id="spinner" style="display:none; width:20%; height:20%">
                <div class="form-group has-feedback">
                    {{ Form::text('fname', null, ['class' => 'form-control', 'placeholder' => 'First name', 'id' => 'fname']) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <label id="fname-error" class="error my-error" for="fname"></label>
                </div>
                <div class="form-group has-feedback">
                    {{ Form::text('lname', null, ['class' => 'form-control', 'placeholder' => 'Last name', 'id' => 'lname']) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <label id="lname-error" class="error my-error" for="lname"></label>
                </div>
                <div class="form-group has-feedback">
                    {{ Form::email('email', '', array('class' => 'form-control', 'id' => 'email', 'required' => 'required', 'placeholder'=>'Email')) }}
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    <label id="email-error" class="error my-error" for="email"></label>
                </div>
                <div id="info_username" class='text-info' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username', 'id' => 'lname']) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <label id="username-error" class="error my-error" for="username"></label>
                </div>
                <div class="form-group has-feedback">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'password']) }}
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    <label id="password-error" class="error my-error" for="password"></label>
                </div>
                <div id="info_password" class='text-info' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    {{ Form::select('user_type', $user_types, '', array('class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select User Type', 'id' => 'user_type')) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    <label id="user_type-error" class="error my-error" for="user_type"></label>
                </div>
                <div class="form-group has-feedback" style="display:none;">
                    {{ Form::select('subject[]', $subjects, '', array('class' => 'form-control subject', 'placeholder' => 'Select Subjects', 'id' => 'subject', 'multiple' => 'multiple', 'style' => 'width:100%')) }}
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="checkbox icheck">
                            <label>
                                <a href="/" class="text-center">I already have an account</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                    </div>
                </div>
            {{ Form::close() }}

            
        </div>
    </div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.js"></script>
    <script id="footer" footer="footer" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/validate.js') }}"></script>
@endsection