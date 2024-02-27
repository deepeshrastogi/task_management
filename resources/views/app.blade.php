<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('task_management/assets/img/favicon.ico') }}">
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
                            <div class="container loader_container">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="loader">
                                            <p>Loading...</p>
                                            <div class="loader-inner"></div>
                                            <div class="loader-inner"></div>
                                            <div class="loader-inner"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        <script src="{{ asset('task_management/js/vue.global.js') }}"></script>
        <script src="{{ asset('task_management/js/axios.min.js') }}"></script>
        
        @yield('scripts')
        <script>
            token = localStorage.getItem('token');
            var user = localStorage.getItem('user');
            var userObject = JSON.parse(user);
            $(document).ready(function(e) {
                let userHtml = `<i class="fas fa-user fa-fw"></i>`+userObject.name+`</a>`;
                $(".user_details").html(userHtml);
                if (!token) {
                    window.location = "{{ route('user.login') }}";
                }
            });
            
            $(document).on("click", ".logoutBtn", function(e) {
                e.preventDefault(0);
                logout();
            });

            function logout(){
                apiUrl = "{{ route('api.user.logout') }}";
                fetch(apiUrl,{
                    method:"GET",
                    headers: {
                        "Content-type": "application/json",
                        "accept": "application/json",
                        "Authorization":"Bearer "+token
                    }
                })
                .then(res => res.json())
                .then(result => {
                    window.location = "{{ route('user.login') }}";
                });
                localStorage.clear();
               
            }
        </script>
    </body>
</html>
