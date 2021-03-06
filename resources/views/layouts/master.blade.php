<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>@yield('title')</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ asset('css/dist/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dist/font-awesome.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dist/ionicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dist/AdminLTE.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dist/blue.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dist/_all-skins.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dist/bootstrap-datepicker.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    </head>
   
    @yield('content')

    @yield('footer')
  </body>
</html>