@extends('layouts.master')
@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <p>Manage Subjects</p>
                    </div>
                    <a href="/manage_subjects" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <!-- <h3>0</h3> -->
                        <p>Manage class</p>
                    </div>
                    <a href="/manage_class" class="small-box-footer">More info<i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Add tasks</p>
                    </div>
                    <a href="/task_management" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <p>Add holidays</p>
                    </div>
                    <a href="/holiday" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
@endsection
@section('footer')
    @include('layouts.footer')
    <script id="footer" footer="" src="{{ mix('/js/footer.js') }}"></script>
@endsection
