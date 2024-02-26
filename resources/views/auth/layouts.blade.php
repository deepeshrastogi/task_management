<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        <link href="{{ asset('task_management/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('task_management/css/custom.css') }}" rel="stylesheet" />
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        @yield('content')
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                @include('inc/footer')
            </div>
        </div>
        <script src="{{ asset('task_management/js/jquery.min.js') }}"></script>
        <script src="{{ asset('task_management/js/custom.js') }}"></script>
        @yield('scripts')
    </body>
</html>
