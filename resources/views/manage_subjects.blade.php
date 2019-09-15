@extends('layouts.master')
@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <input type="hidden" id="user_id" name="user_id" value="{{ Auth::id() }}">
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
                <!-- TO DO List -->
                <div class="box box-primary">
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
                        <ul class="todo-list todo">
                            <li class="editable" subject_id="" style="display:none">
                                <span class="text"></span>
                                <div class="tools">
                                    <!-- <i class="fa fa-edit edit"></i> -->
                                    <i class="fa fa-trash-o remove"></i>
                                </div>
                            </li>
                        </ul>
                        <ul class="todo-list">
                            <li name="subject" id="subject" style="display:none;">
                                <textarea style="width: 100%"></textarea>
                            </li>
                        </ul>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer clearfix no-border">
                        <button type="button" style="display: none" class="btn btn-success pull-right add" user_id="{{ Auth::id() }}">Add</button>
                        <button type="button" class="btn btn-default add_item pull-right"><i class="fa fa-plus"></i> Add new subject</button>
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
    <script src="{{ mix('/js/manage_subjects.js') }}"></script>
@endsection
