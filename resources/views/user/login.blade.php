@extends('auth.layouts')
@section('title')
User | Login
@endsection
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Login</div>
                <div class="card-body">
                    <form id="loginForm">
                        <div class="auth_error"></div>
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
                            <a href="{{ route('user.signup') }}" class="mt-2 col-md-4 offset-md-4 btn btn-link">Click here to sign up</a>
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
            $(".loader_container").hide();
        });
        $(document).on("click", ".login", function(e) {
            e.preventDefault();
            login();
        });

        function login() {
            // $(".loader_container").show();
            apiUrl = "{{ route('api.user.login') }}";
            var formData = new FormData();
            let email = $("#loginForm").find("#email").val();
            let password = $("#loginForm").find("#password").val();
            let errors = checkValidations(email,password);
            if('email' in errors || 'password' in errors){
                $("#loginForm").find(".text-danger").remove();
                if('email' in errors){
                    let error = `<span class="text-danger">` + errors['email'] + `</span>`;
                    $("#loginForm").find(".email").addClass("is-invalid");
                    $("#loginForm").find(".email").after(error);
                }

                if('password' in errors){
                    let error = `<span class="text-danger">` + errors['password'] + `</span>`;
                    $("#loginForm").find(".password").addClass("is-invalid");
                    $("#loginForm").find(".password").after(error);
                }
                return false;
            }
            $(".loader_container").show();
            formData.append('email', email);
            formData.append('password', password);
            let response = ajaxCall(apiUrl, formData);
            response.done(function(data) {
                localStorage.setItem('token', data.content.token);
                localStorage.setItem('user', JSON.stringify(data.content.user));
                window.location.href = "{{ route('user.dashboard') }}?title=You are welcome to task management system";
                $(".loader_container").hide();
            }).fail(function(data) {
                $("#loginForm").find(".text-danger").remove();
                $.each(data.responseJSON.error, function(key, value) {
                    if(value == "Unauthorized"){
                        let error = ` <div class="alert alert-danger alert-dismissible fade show alertMessage" role="alert">These credentials do not match our records.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`
                        $("#loginForm").find(".auth_error").html(error);
                        setTimeout(() => {
                            error = '';
                            $('.alertMessage').fadeOut('slow');
                        }, 2000);
                    }else{
                        let error = `<span class="text-danger">` + value[0] + `</span>`;
                        $("#loginForm").find("." + key).addClass("is-invalid");
                        $("#loginForm").find("." + key).after(error);
                    }
                });
                $(".loader_container").hide();
            });
        }

        function checkValidations(email,password){
            let errors = [];
            if(!checkEmail(email)){
                errors['email'] = ['The email is invalid.'];
            }

            if(isEmpty(email)){
                errors['email'] = ['The email field is required.'];
            }

            if(isEmpty(password)){
                errors['password'] = ['The password field is required.'];
            }
            return errors;
        }
    </script>
@endsection
