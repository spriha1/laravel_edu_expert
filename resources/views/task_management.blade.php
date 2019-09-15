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
            <form class="form-horizontal" id="task" name="task" method="POST">
                <div id="alert" class='alert alert-success' style="display: none;">
                </div>
                <img src="/images/load.gif" id="spinner" style="display:none; width:20%; height:20%">
                <input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
                <div class="box-header with-border">
                    <h3 class="box-title">Add tasks</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="teacher" class="col-sm-3 control-label">Teacher</label>
                        <div class="col-sm-9">
                            <select class="form-control mb-2 mr-sm-2 teacher" name="teacher" id="teacher">
                                @foreach ($teachers as $teacher)
                                <option value={{ $teacher->id }}>{{ $teacher->firstname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="class" class="col-sm-3 control-label">Class</label>
                        <div class="col-sm-9">
                            <select class="form-control mb-2 mr-sm-2 class" name="class" id="class">
                                
                            </select>
                            <option class="clone_" value=""></option>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="col-sm-3 control-label">Subject</label>
                        <div class="col-sm-9">
                            <select multiple="multiple" class="form-control subject" id="subject" name="subject[]">
                                    
                            </select>
                            <option class="clone" value=""></option>
                        </div>
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
    <script src="{{ mix('/js/task.js') }}"></script>
@endsection
