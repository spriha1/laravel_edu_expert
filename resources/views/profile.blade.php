@extends('layouts.master')
@section('sidenav_content')
@if ($usertype === 'Admin')
    @include('layouts.admin_sidenav')
@elseif ($usertype === 'Teacher')
    @include('layouts.teacher_sidenav')
@elseif ($usertype === 'Student')
    @include('layouts.student_sidenav')
@endif
@endsection
@section('content')
<div class="content-wrapper">
    <br><br>
    <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="box box-info">
            {{ Form::model(Auth::user(), ['class' => 'form-horizontal', 'id' => 'registration', 'name' => 'registration']) }}
                {{ session()->get('profile_msg') }}

                <div id="alert" class='alert alert-danger' style="display: none;">
                </div>
                <div id="info" class='alert alert-success' style="display: none;">
                </div>
                <img src="/images/load.gif" id="spinner" style="display:none; width:20%; height:20%">
                <div class="box-body">
                    <div class="form-group">
                        <label for="fname" class="col-sm-3 control-label">First Name</label>
                        <div class="col-sm-9">
                            {{ Form::text('fname', Auth::user()->firstname, array('class' => 'form-control', 'id' => 'fname', 'readonly' => 'readonly')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lname" class="col-sm-3 control-label">Last Name</label>
                        <div class="col-sm-9">
                            {{ Form::text('lname', Auth::user()->lastname, array('class' => 'form-control', 'id' => 'lname', 'readonly' => 'readonly')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <div id="info_username" class='text-info' style="display: none;">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="username" class="col-sm-3 control-label">Username</label>
                        <div class="col-sm-9">
                            {{ Form::text('username', Auth::user()->username, array('class' => 'form-control', 'id' => 'username', 'readonly' => 'readonly')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">Email</label>
                        <div class="col-sm-9">
                            {{ Form::email('email', Auth::user()->email, array('class' => 'form-control', 'id' => 'email', 'readonly' => 'readonly')) }}
                        </div>
                    </div>
                    @if ($usertype === 'Teacher')
                    @foreach ($rates as $rate)
                    <div class="form-group">
                        <label for="rate" class="col-sm-3 control-label">Rate per hour</label>
                        <div class="col-sm-9">
                            {{ Form::number('rate', $rate->rate, array('class' => 'form-control', 'id' => 'rate', 'readonly' => 'readonly')) }}
                        </div>
                    </div>
                    @endforeach
                    @endif
                    @if ($usertype === 'Admin' || $usertype === 'Teacher')
                    <div class="form-group">
                        <label for="currency" class="col-sm-3 control-label">Currency</label>
                        <div class="col-sm-9">
                            {{ Form::select('currency', $currencies, $currency_id, array('id' => 'currency', 'class' => 'form-control')) }}
                        </div>
                    </div>
                    @endif
                    @if ($usertype === 'Admin')
                    <div class="form-group">
                        <label for="tax" class="col-sm-3 control-label">GST</label>
                        <div class="col-sm-9">
                            {{ Form::text('tax', $tax, array('class' => 'form-control', 'id' => 'tax', 'readonly' => 'readonly')) }}
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <label for="date_format" class="col-sm-3 control-label">Date Format</label>
                        <div class="col-sm-9">
                            <select name="date_format" id="date_format" class="form-control" disabled>
                                <option value="yyyy-mm-dd" {{ Auth::user()->date_format === 'yyyy-mm-dd' ? 'selected="selected"' : '' }}>yyyy-mm-dd</option>

                                <option value="yyyy/mm/dd" {{ Auth::user()->date_format === 'yyyy/mm/dd' ? 'selected="selected"' : '' }}>yyyy/mm/dd</option>

                                <option value="yyyy.mm.dd" {{ Auth::user()->date_format === 'yyyy.mm.dd' ? 'selected="selected"' : '' }}>yyyy.mm.dd</option>

                                <option value="dd-mm-yyyy" {{ Auth::user()->date_format === 'dd-mm-yyyy' ? 'selected="selected"' : '' }}>dd-mm-yyyy</option>

                                <option value="dd/mm/yyyy" {{ Auth::user()->date_format === 'dd/mm/yyyy' ? 'selected="selected"' : '' }}>dd/mm/yyyy</option>

                                <option value="dd.mm.yyyy" {{ Auth::user()->date_format === 'dd.mm.yyyy' ? 'selected="selected"' : '' }}>dd.mm.yyyy</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="pass" style="display: none">
                        <label for="password" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-9">
                            {{ Form::password('password', array('class' => 'form-control', 'id' => 'password')) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <div id="info_password" class='text-info' style="display: none;"></div>
                        </div>
                    </div>
                    <div id="info_password" class='text-info' style="display: none;">
                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">Address</label>
                        <div id="geocoder" class="col-sm-9">
                        </div>
                    </div>
                    {{ Form::hidden('lat', Auth::user()->latitude, array('id' => 'lat')) }}
                    {{ Form::hidden('long', Auth::user()->longitude, array('id' => 'long')) }}
                    {{ Form::hidden('address', Auth::user()->address, array('id' => 'address')) }}
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {{ Form::submit('Change password', ['class' => 'btn btn-default', 'id' => 'change']) }}
                    {{ Form::submit('Edit', ['class' => 'btn btn-info pull-right', 'id' => 'edit']) }}
                    {{ Form::submit('Update', ['class' => 'btn btn-info pull-right', 'id' => 'update', 'style' => 'display:none;']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
    <!-- <div class="col-md-6">
        <div id="googleMap" style="width:400px;height:400px;"></div>
    </div> -->
    <div class="col-md-6" id="map" style="height:60vh"></div>
</div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/edit.js') }}"></script>
@endsection