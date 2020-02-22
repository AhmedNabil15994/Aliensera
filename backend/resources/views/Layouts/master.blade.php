<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AlienSera | @yield('title')</title>
        @include('Layouts.head')
        @yield('styles')
        @yield('otherhead')
    </head>
    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                @include('Layouts.sidebar-menu')
                @include('Layouts.header')
                <div class="right_col" role="main">
                    @yield('content')
                </div>
                <footer>
                    <div class="pull-right">
                    </div>
                    <div class= "clearfix"></div>
                </footer>
            </div>
        </div>

        @include('Layouts.footer')
        @include('Partials.notf_messages')
        
    </body>
</html>
