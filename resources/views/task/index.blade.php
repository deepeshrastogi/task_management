@extends('app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-11">
            <table class="table">
                <tr>
                    <th>Id</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>
                        <button class="btn btn-sm row_1_plus"><span class="fa fa-plus"></span></button> 
                        <button class="btn btn-sm row_1_minus d-none"><span class="fa fa-minus"></span></button> 
                        1
                    </td>
                    <td>Title</td>
                    <td>Content</td>
                    <td>
                        <select>
                            <option>To Do</option>
                            <option>Inprogress</option>
                            <option>Done</option>
                        </select>
                    </td>
                    <td>

                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <center>30%</center>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-info"><span class="fa fa-eye"></span></button>
                        <button class="btn btn-sm btn-success"><span class="fa fa-edit"></span></button>
                        <button class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                    </td>
                </tr>
                    <tr class="row_1_child d-none">
                        <td> &nbsp; 
                            1
                        </td>
                        <td>Title</td>
                        <td>Content</td>
                        <td>
                            <select class="form-control">
                                <option>To Do</option>
                                <option>Inprogress</option>
                                <option>Done</option>
                            </select>
                        </td>
                        <td>
    
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: 33%" aria-valuenow="33.33" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <center>30%</center>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-info"><span class="fa fa-eye"></span></button>
                            <button class="btn btn-sm btn-success"><span class="fa fa-edit"></span></button>
                            <button class="btn btn-sm btn-danger"><span class="fa fa-trash"></span></button>
                        </td>
                    </tr> 
                </span>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
       $(document).ready(function(){
            getTasksList();
       });

       function getTasksList(){
            let ajaxUrl = "{{ route('task.list') }}";
            let apiToken = localStorage.getItem('token');
            let apiURL = "{{ route('api.task.list') }}";
            var formData = new FormData();
            formData.append('api_url',apiURL);
            formData.append('_token','{{ csrf_token() }}');
            formData.append('api_token',apiToken);
            formData.append('per_page',10);
            formData.append('search','');
            formData.append('status','');
            
            let response = ajaxCall(ajaxUrl, formData);
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
        // $(document).on("click", ".login", function(e) {
        //     // e.preventDefault();
        //     // login();
        // });
        // $(document).on("click", ".row_1_plus", function(e) {
        //     $(this).addClass("d-none");
        //     $(".row_1_minus").removeClass("d-none");
        //     $(".row_1_child").removeClass("d-none");
        //     $(".row_1_child").fadeIn('1000');
        // });

        // $(document).on("click", ".row_1_minus", function(e) {
        //     $(this).addClass("d-none");
        //     $(".row_1_plus").removeClass("d-none");
        //     $(".row_1_child").addClass("d-none");
        //     $(".row_1_child").fadeOut('1000');
        // });
    </script>
@endsection
