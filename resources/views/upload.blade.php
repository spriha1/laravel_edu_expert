@extends('layouts.master')
@section('sidenav_content')
@include('layouts.teacher_sidenav')
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <form action="/post_upload" class="form-inline" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <input type="file" class="form-control" id="cv" name="cv">
                        <input type="submit" class="form-control btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

@endsection
@section('footer')
    @include('layouts.footer')
@endsection
