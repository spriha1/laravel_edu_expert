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
            <form action="login" method="POST" id="login" name="login">
                @csrf
                @if ($error = $errors->first('password'))
                    <div class="alert alert-danger">
                        {{ $error }}
                    </div>
                @endif
                <div id="alert" class='alert alert-danger' style="display: none;">
                </div>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" placeholder="Username" id="username" name="username" value="{{ old('username') }}">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" placeholder="Password" id="password" name="password">
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
                        <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
                    </div>
                </div>
            </form>

            <a href="register" class="text-center">Register Here</a>
            <br>
            <button class="btn btn-light btn-block btn-flat">
                <a href="/login/google" class="text-center">
                    <img src="/images/google.png">
                    Sign In with Google
                </a>
            </button>
            <br>
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
    
    <!-- <script src="{{ mix('/js/validate.js') }}"></script> -->

   <!--  <script src="{{ asset('js/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/dist/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/dist/icheck.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script> -->
@endsection