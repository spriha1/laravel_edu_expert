@extends('layouts.master')
@section('sidenav_content')
@include('layouts.teacher_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_FofqsPRhieqcEAWVQxdPC3LNbj5KMvaZ&scope=read_write&redirect_uri=https://eduexpert.local.com/teacher_dashboard/" class="btn btn-info pull-right">Connect with Stripe</a>
        <h1>
        Dashboard
        </h1>
        <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
        <input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <p>Daily Time Table</p>
                    </div>
                    <a href="/daily_teacher_timetable" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <p>Weekly Teacher Timetable</p>
                    </div>
                    <a href="/weekly_teacher_timetable" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Student Timesheet</p>
                    </div>
                    <a href="/student_timesheets" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
                <!-- TO DO List -->
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="ion ion-clipboard"></i>
                        <h3 class="box-title">Plan for the day</h3>
                        <input class="datepicker">
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body" id="plan">
                        <ul class="todo-list todo">
                        </ul>
                        <li class="editable" goal_id="" style="display:none">
                            <input type="checkbox" class="check_goal">          
                            <span class="text"></span>
                            <small class="label label-danger time" id="" style="visibility: hidden"><i class="fa fa-clock-o total_time"></i></small>
                            <div class="tools">
                                <i class="fa fa-trash-o remove" goal_id=""></i>
                            </div>
                        </li>
                        <ul class="todo-list">
                            <li name="goal" id="goal" style="display:none;">
                                <textarea style="width: 100%"></textarea>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" style="display: none" class="btn btn-success pull-right add" user_id="{{ Auth::user()->id }}">Add</button>
                        <button type="button" class="btn btn-default add_item pull-right"><i class="fa fa-plus"></i> Add item</button>
                    </div>
                </div>
                <!-- /.box -->
            </section>
            <!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">
            </section>
            <!-- right col -->
        </div>
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="dashboard_footer" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/goals.js') }}"></script>
@endsection
