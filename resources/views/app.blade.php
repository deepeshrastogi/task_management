<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" /> --}}
        <link href="{{ asset('task_management/css/styles.css') }}" rel="stylesheet" />
        <link href="{{ asset('task_management/css/custom.css') }}" rel="stylesheet" />
    </head>
    <body class="font-sans antialiased">
        <div class="main">
            <div>
                @include('inc.header')
                <div id="layoutSidenav">
                    @include('inc.sidebar')
                    <div id="layoutSidenav_content">
                        <main>
                            @yield('content')
                        </main>
                        @include('inc.footer')
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('task_management/js/all.min.js') }}"></script>
        <script src="{{ asset('task_management/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{ asset('task_management/js/scripts.js') }}"></script>
        <script src="{{ asset('task_management/js/jquery.min.js') }}"></script>
        <script src="{{ asset('task_management/js/custom.js') }}"></script>
        @yield('scripts')
        <script>
            $(document).on("click", ".logout", function(e) {
                e.preventDefault(0);
                logout();
            });

            function logout(){
                let token = localStorage.getItem('token');
                apiUrl = "{{ route('api.user.logout') }}";
                // localStorage.clear();
                // window.location = "{{ route('user.login') }}";
            }
        </script>
    </body>
</html>
