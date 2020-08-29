<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- CSRF Token --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
        <meta name="description" content="">
        <meta name="author" content="Jeremy Kenedy">
        <link rel="shortcut icon" href="/favicon.ico">

        {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        {{-- Fonts --}}
        @yield('template_linked_fonts')

        {{-- Styles --}}
        <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

        @yield('template_linked_css')

        <style type="text/css">
            @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
                    background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
                    background-size: auto 100%;
                }
            @endif

        </style>

        {{-- Scripts --}}
        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>

        @if (Auth::User() && (Auth::User()->profile) && $theme->link != null && $theme->link != 'null')
            <link rel="stylesheet" type="text/css" href="{{ $theme->link }}">
        @endif

        @yield('head')

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" />
        <script src="https://www.malot.fr/bootstrap-datetimepicker/js/jquery.js"></script>
        <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>

        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css') }}">
        <link href="{{ url('plugins/select2/css/select2.css') }}" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" media="all" href="{{ url('css/jquery.minicolors.css') }}">
    </head>
    <body>
        <div id="app">
            @guest
                @yield('content')
            @else
                @include('partials.nav')
            
                <main class="py-4">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                @include('partials.form-status')
                            </div>
                        </div>
                    </div>

                    @yield('content')
                </main>
            @endguest

        </div>

        {{-- Scripts --}}
        <script src="{{ mix('/js/app.js') }}"></script>
        <script src="{{ asset('plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
        <script src="{{ url('plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ url('js/jquery.minicolors.min.js') }}"></script>
        @yield('footer_scripts')

    </body>
</html>
