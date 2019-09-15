@extends('layouts.master')
@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <br><br>
    <div class="col-md-6">
        <!-- Horizontal Form -->
        <div class="box box-info">
            <form class="form-horizontal" id="holiday" name="holiday" method="POST">
                <div id="alert" class='alert alert-success' style="display: none;">
                </div>
                <img src="load.gif" id="spinner" style="display:none; width:20%; height:20%">
                <input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
                <div class="box-header with-border">
                    <h3 class="box-title">Add holiday</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="day" class="col-sm-3 control-label">Select Day</label>
                        <div class="col-sm-9">
                            <select multiple="multiple" class="form-control day" id="day" name="day[]">
                                    <option value="0">Sunday</option>
                                    <option value="1">Monday</option>
                                    <option value="2">Tuesday</option>
                                    <option value="3">wednesday</option>
                                    <option value="4">Thursday</option>
                                    <option value="5">Friday</option>
                                    <option value="6">Saturday</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Or</label>
                    </div>
                    <div class="form-group">
                        <label for="start_date" class="col-sm-3 control-label">Start Date</label>
                        <div class="col-sm-9">
                            <input id="start_date" name="start_date" class="datepicker">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_date" class="col-sm-3 control-label">End Date</label>
                        <div class="col-sm-9">
                            <input id="end_date" name="end_date" class="datepicker">
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-info pull-right">Add</button>
                </div>
                <!-- /.box-footer -->
            </form>
        </div>
    </div>
</div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/holiday.js') }}"></script>
@endsection
