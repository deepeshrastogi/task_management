@extends('auth.layouts')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email <span class="error">*</span></label>
                            <div class="col-md-6">
                                <input type="email" class="form-control email" id="email" name="email"
                                    value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password <span class="error">*</span></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control password" id="password" name="password">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <button type="button" class="col-md-3 offset-md-5 btn btn-primary login">Login</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            let token = localStorage.getItem('token');
            if (token) {
                window.location = "{{ route('user.dashboard') }}";
            }
        });
        $(document).on("click", ".login", function(e) {
            e.preventDefault();
            login();
        });

        function login() {
            apiUrl = "{{ route('api.user.login') }}";
            var formData = new FormData();
            let email = $("#loginForm").find("#email").val();
            let password = $("#loginForm").find("#password").val();
            formData.append('email', email);
            formData.append('password', password);
            let response = ajaxCall(apiUrl, formData);
            response.done(function(data) {
                localStorage.setItem('token', data.content.token);
                localStorage.setItem('user', JSON.stringify(data.content.user));
                window.location = "{{ route('user.dashboard') }}";
            }).fail(function(data) {
                $("#loginForm").find(".text-danger").remove();
                $.each(data.responseJSON.error, function(key, value) {
                    let error = `<span class="text-danger">` + value[0] + `</span>`;
                    $("#loginForm").find("." + key).addClass("is-invalid");
                    $("#loginForm").find("." + key).after(error);
                });
            });
        }
    </script>
@endsection
