@extends('layouts.master')
@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <br><br>
    <form class="form-inline">
        @csrf
        <div>
            @if(Session::has('error'))
                {{ Session::get('error') }}
            @endif
        </div>
        <div class="form-group mx-auto">
        <select class="form-control mb-2 mr-sm-2" id="user_type" name="user_type">
            <option value=-1>Select User Type</option>
            @foreach($user_types as $user_type)
                <option value={{ $user_type->user_type }} {{ ($user_type->user_type === $search)?"selected":"" }}>{{ $user_type->user_type }}
                </option>
            @endforeach
        </select>
        </div>
        <div class="form-group mx-auto">
            <button class="btn btn-success form-control mr-sm-2 mb-2" id="go" type="submit">Go</button>
        </div>
    </form>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <input type="hidden" id="username" name="username" value="{{ Auth::user()->username }}">
                        <input type="hidden" id="view" value="regd">
                        <table id="regd_users" class="table table-bordered table-striped table-responsive-sm" width="100%">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <!-- <th></th> -->
                                    <th>Actions</th>
                                    <!-- <th>Assign Class</th> -->
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/users_list.js') }}"></script>
@endsection
