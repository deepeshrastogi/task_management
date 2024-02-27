@extends('auth.layouts')
@section('title')
User | Signup
@endsection
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">Sign up</div>
                <div class="card-body">
                    <form id="signupForm">
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name <span class="error">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control name" id="name" name="name"
                                    value="">
                            </div>
                        </div>
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
                            <label for="confirm_password" class="col-md-4 col-form-label text-md-end text-start">Confirm Password <span class="error">*</span></label>
                            <div class="col-md-6">
                                <input type="password" class="form-control confirm_password" id="confirm_password"
                                    name="confirm_password">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <button type="button" class="col-md-3 offset-md-5 btn btn-primary singUp">Sign Up</button>
                            <a href="{{ route('user.login') }}" class="mt-2 col-md-4 offset-md-4 btn btn-link">Click here to login</a>
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
        $(document).on("click", ".singUp", function(e) {
            e.preventDefault();
            signUp();
        });

        function signUp() {
            apiUrl = "{{ route('api.user.signup') }}";
            var formData = new FormData();
            let name = $("#signupForm").find("#name").val();
            let email = $("#signupForm").find("#email").val();
            let password = $("#signupForm").find("#password").val();
            let confirm_password = $("#signupForm").find("#confirm_password").val();
            let errors = checkValidations(email,password,name,confirm_password);
            if('email' in errors || 'password' in errors || 'name' in errors || 'confirm_password' in errors){
                $("#signupForm").find(".text-danger").remove();
                if('email' in errors){
                    let error = `<span class="text-danger">` + errors['email'] + `</span>`;
                    $("#signupForm").find(".email").addClass("is-invalid");
                    $("#signupForm").find(".email").after(error);
                }

                if('password' in errors){
                    let error = `<span class="text-danger">` + errors['password'] + `</span>`;
                    $("#signupForm").find(".password").addClass("is-invalid");
                    $("#signupForm").find(".password").after(error);
                }

                if('name' in errors){
                    let error = `<span class="text-danger">` + errors['name'] + `</span>`;
                    $("#signupForm").find(".name").addClass("is-invalid");
                    $("#signupForm").find(".name").after(error);
                }

                if('confirm_password' in errors){
                    let error = `<span class="text-danger">` + errors['confirm_password'] + `</span>`;
                    $("#signupForm").find(".confirm_password").addClass("is-invalid");
                    $("#signupForm").find(".confirm_password").after(error);
                }
                return false;
            }
            formData.append('name', name);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('confirm_password', confirm_password);
            let response = ajaxCall(apiUrl, formData);
            response.done(function(data) {
                localStorage.setItem('token', data.content.token);
                localStorage.setItem('user', JSON.stringify(data.content.user));
                window.location = "{{ route('user.dashboard') }}";
            }).fail(function(data) {
                $("#signupForm").find(".text-danger").remove();
                $.each(data.responseJSON.error, function(key, value) {
                    let error = `<span class="text-danger">` + value[0] + `</span>`;
                    $("#signupForm").find("." + key).addClass("is-invalid");
                    $("#signupForm").find("." + key).after(error);
                });
            });
        }

        function checkValidations(email,password,name,confirm_password){
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

            if(isEmpty(name)){
                errors['name'] = ['The name field is required.'];
            }
            
            if(isEmpty(confirm_password)){
                errors['confirm_password'] = ['The confirm password field is required.'];
            }

            if(confirm_password != password){
                errors['confirm_password'] = ['The confirm password field must match password.'];
            }

            return errors;
        }
    </script>
@endsection
