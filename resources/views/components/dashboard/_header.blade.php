<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow w-full">
    <a class="inline-block no-underline px-4 col-md-3 col-lg-2 font-semibold text-lg text-white" href="/">Fiblo</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu"
        aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <div class="navbar-nav">
        <div class="nav-item text-nowrap">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button class="nav-link px-3 border-0 bg-dark" type="submit">
                    <i data-feather="log-out"></i> Logout
                </button>
            </form>
        </div>
    </div>
</header>