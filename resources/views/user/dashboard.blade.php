@extends('app')
@section('title')
    User | Dashboard
@endsection
@section('content')
    @php
        $taskCount = App\models\Task::getTaskCount();
    @endphp

    <div class="container mt-2">
        @if (!empty(app('request')->input('title')))
            <div class="alert alert-success alert-dismissible fade show alertMessage" role="alert">
                {{ app('request')->input('title') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
    <div class="row mt-5 ml-5">
        <div class="col-xl-1 col-md-1"></div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body"><i class="fas fa-tasks"></i> Tasks ({{ $taskCount }})</div>
                <div class="card-footer d-flex align-items-center justify-content-between">
                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
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
        });
    </script>
@endsection
