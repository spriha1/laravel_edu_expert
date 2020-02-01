@extends('layouts.master')
@section('sidenav_content')
@include('layouts.teacher_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <input id="date" class="datepicker">
                        <button type="button" id="share" class="btn btn-success pull-right">Submit</button>
                    </div>
                    <input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
                    <div class="box-body">
                        <input type="hidden" id="user_id" value="{{ Auth::id() }}">
                        <input type="hidden" id="user_type" value="teacher">
                        <table id="timetable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="25%">Subject</th>
                                    <th width="25%">Class</th>
                                    <th width="25%"></th>
                                    <th width="25%"></th>
                                </tr>
                            </thead>
                            <tbody class="timetable">
                            </tbody>
                        </table>
                        <table style="display:none">
                        <tr class="editable" width="25%" task_id="" style="display:none;">
                            <td class="name" width="25%"></td>
                            <td class="class" width="25%"></td>
                            <td width="25%">
                                <div class="timer"></div>
                            </td>
                            <td>
                                <button class="btn btn-info start">Start</button>
                                <button class="btn btn-info stop" style="display:none" task_id="">Stop</button>
                                <button class="btn btn-info pause" style="display:none">Pause</button>
                                <button class="btn btn-info resume" style="display:none">Resume</button>
                            </td>
                        </tr>
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
    <script id="footer" footer="profile_footer" src="{{ mix('/js/footer.js') }}"></script>
    <script src="{{ mix('/js/daily_timetable.js') }}"></script>
@endsection
