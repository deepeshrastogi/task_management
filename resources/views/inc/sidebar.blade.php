<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="{ route('dashboard') }">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Books</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#categoryModule" aria-expanded="false" aria-controls="categoryModule">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Books
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="categoryModule" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a href="{ route('book.index') }" class="nav-link">Book Lists</a>
                        <a href="{ route('book.create') }" class="nav-link">Add New Book</a>
                    </nav>
                </div>
            </div>
        </div>
    </nav>
</div>