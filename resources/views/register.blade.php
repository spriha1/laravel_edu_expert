@extends('layouts.master')

@section('title', 'Edu Expert')

@section('content')
    <body class="hold-transition register-page">
    <div class="register-box">
        <div class="register-logo">
            Edu<b>Xpert</b>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg">Create Account</p>

            <form method="POST" action="" id="registration" name="registration">
                @csrf
                <div id="alert" class='alert alert-danger' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="First name" id="fname" name="fname">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Last name" id="lname" name="lname">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="email" class="form-control" placeholder="Email" id="email" name="email">
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div id="info_username" class='text-info' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username" id="username" name="username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div id="info_password" class='text-info' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    <select class="form-control" id="user_type" name="user_type">
                        <option value="0">Select User Type</option>
                        @foreach ($user_types as $user_type)
                            <option value="{{ $user_type->user_type }}"> {{ $user_type->user_type }} </option>
                        @endforeach
                    </select>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback" style="display:none;">
                    <select class="form-control subject" id="subject" name="subject[]" multiple="multiple" style="width:100%">
                        <option value="0">Select Subjects</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"> {{ $subject->name }} </option>
                        @endforeach
                    </select>
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
            </form>
        </div>
    </div>
@endsection

@section('footer')
    <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dist/icheck.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
    <script id="footer" footer="footer" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/validate.js') }}"></script>
@endsection