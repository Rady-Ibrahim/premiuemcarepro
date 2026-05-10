<nav class="main-navbar">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="logo">
            <div class="tog-active d-block d-lg-none" data-tog="true" data-active=".app">
                <i class="fas fa-bars"></i>
            </div>
            <img src="{{ asset('admin-asset/img/logo.png') }}" alt="logo" class="img">

        </div>

        <div class="d-flex align-items-center gap-2rem">




            <div class="dropdown info-user ms-auto">
                <button class="dropdown-toggle d-flex align-items-center gap-2 content" type="button"
                        id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="text">
                        <div class="name">
                            <i class="fas fa-angle-down"></i>
                            {{ auth()->user()->name }}
                        </div>
                        <div class="dic">
                           ادمن
                        </div>
                    </div>
                    <div class="img">
                        <img src="{{ asset('admin-asset/img/icons/user-black.svg') }}" alt="" class="icon"/>
                    </div>
                </button>


                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    @auth
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item" type="submit">تسجيل خروج
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>
</nav>
