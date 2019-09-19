@extends('layouts.master')
@section('sidenav_content')
@include('layouts.admin_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <input type="hidden" name="date_format" id="date_format" value="{{ Auth::user()->date_format }}">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="balance" class="col-sm-3 control-label">Available balance</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="balance" name="balance" readonly value="{{ $amount }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@section('footer')
    @include('layouts.footer')
@endsection
