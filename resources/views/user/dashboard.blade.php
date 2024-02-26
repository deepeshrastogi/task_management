@extends('app')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                  <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                  </li>
                </ul>
              </nav>

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

            <form>
                <div class="row">
                    <div class="col-6">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <input type="text" class="form-control" id="content">
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_publish">
                            <label class="form-check-label" for="is_publish">Saved as draft/Published</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="attachement" class="form-label">Attachement</label>
                        <input type="file" class="form-control" id="attachement">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(e) {
            let token = localStorage.getItem('token');
            if (!token) {
                window.location = "{{ route('user.login') }}";
            }
        });
        $(document).on("click", ".login", function(e) {
            // e.preventDefault();
            // login();
        });
        $(document).on("click", ".row_1_plus", function(e) {
            $(this).addClass("d-none");
            $(".row_1_minus").removeClass("d-none");
            $(".row_1_child").removeClass("d-none");
            $(".row_1_child").fadeIn('1000');
        });

        $(document).on("click", ".row_1_minus", function(e) {
            $(this).addClass("d-none");
            $(".row_1_plus").removeClass("d-none");
            $(".row_1_child").addClass("d-none");
            $(".row_1_child").fadeOut('1000');
        });
    </script>
@endsection
