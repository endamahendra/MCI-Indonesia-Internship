        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="sidebar-sticky pt-3">
                <div class="card bg-white sidebar-card">
                    <div class="card-body">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('user.dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('update.user', ['id' => Auth::user()->id]) }}">
                                    Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('forder.user') }}">
                                    Order
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
