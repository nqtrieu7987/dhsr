<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
<!-- Tell the browser to be responsive to screen width -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ url('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Ionicons -->
<link rel="stylesheet" href="{{ url('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
<!-- Tempusdominus Bbootstrap 4 -->
<link rel="stylesheet" href="{{ url('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<!-- iCheck -->
<link rel="stylesheet" href="{{ url('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<!-- JQVMap -->
<link rel="stylesheet" href="{{ url('plugins/jqvmap/jqvmap.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ url('dist/css/adminlte.min.css') }}">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ url('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{ url('plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ url('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
<!-- summernote -->
<link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.css') }}">
<!-- Google Font: Source Sans Pro -->
<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ url('plugins/select2/css/select2.css') }}" rel="stylesheet" />
{{-- <link href="{{ URL::asset('/css/lobibox.min.css')}}" rel="stylesheet" media="all"> --}}
<link href="{{ URL::asset('/css/lobibox.css')}}?v=2" rel="stylesheet" media="all">

<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}?v=end">
<link href="{{ mix('/css/dshr.css') }}" rel="stylesheet">
@yield('template_linked_css')
</head>