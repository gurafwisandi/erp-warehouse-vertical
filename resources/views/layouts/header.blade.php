<div class="topbar-main">
    <div class="container-fluid">
        <div class="logo">
            {{-- <a href="index.html" class="logo">
                <img src="assets/images/logo.png" alt="" class="logo-large">
            </a> --}}
            PT. LAUTAN STEEL INDONESIA - <span class="badge badge-success">{{ Auth::user()->roles }}</span>
        </div>
        <div class="menu-extras topbar-custom">
            <ul class="list-inline float-right mb-0">
                <li class="list-inline-item dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown"
                        href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        @if (Auth::user()->foto)
                            <img src="{{ URL::asset('files/foto/' . Auth::user()->foto) }}" alt="user"
                                class="rounded-circle">
                        @else
                            <img src="{{ URL::asset('assets/images/users/avatar-1.jpg') }}" alt="user"
                                class="rounded-circle">
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <div class="dropdown-item noti-title">
                            <h5>Welcome</h5>
                        </div>
                        <?php $id = Crypt::encryptString(Auth::user()->id); ?>
                        <a class="dropdown-item" href="{{ route('user.profile', $id) }}"><i
                                class="mdi mdi-account-circle m-r-5 text-muted"></i> Profile</a>
                        <div class="dropdown-divider"></div>
                        <form class="logout" action="{{ route('login.logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item"><i class="mdi mdi-logout m-r-5 text-muted"></i>
                                Logout</button>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
