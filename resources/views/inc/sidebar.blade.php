<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="{{ route('user.dashboard') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Tasks</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#taskModule" aria-expanded="false" aria-controls="taskModule">
                    <div class="sb-nav-link-icon"><i class="fas fa-tasks"></i></div>
                    Tasks
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="taskModule" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a href="{{ route('task.index') }}" class="nav-link">Tasks List</a>
                        <a href="{{ route('task.create') }}" class="nav-link">Create Task</a>
                        <a href="{{ route('task.subtask.create') }}" class="nav-link">Create Sub Task</a>
                        <a href="{{ route('task.trashed') }}" class="nav-link">Trash</a>
                    </nav>
                </div>

                <a class="nav-link logoutBtn" href="#!">
                    <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                    Logout
                </a>
            </div>
        </div>
    </nav>
</div>