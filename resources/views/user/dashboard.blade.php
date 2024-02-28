@extends('app')
@section('title')
    User | Dashboard
@endsection
@section('content')
    <div class="container mt-2">
        @if (!empty(app('request')->input('title')))
            <div class="alert alert-success alert-dismissible fade show alertMessage" role="alert">
                {{ app('request')->input('title') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row mt-5 ml-5">
            <div class="col-xl-1 col-md-1"></div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body"><i class="fas fa-tasks"></i> Tasks <span class="task_count">(0)</span></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('task.index') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body"><i class="fas fa-trash"></i> Trashed Task <span class="trashed_task_count">(0)</span></div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('task.trashed') }}">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
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
            dashboardData()
        });

        function dashboardData() {
            $(".loader_container").show();
            apiUrl = "{{ route('api.user.dashboard') }}";
            let formData = {};
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
                $('.task_count').html('('+result.content.task_count+')');
                $('.trashed_task_count').html('('+result.content.trashed_task_count+')');
                $(".loader_container").hide();
            });
        }
    </script>
@endsection
