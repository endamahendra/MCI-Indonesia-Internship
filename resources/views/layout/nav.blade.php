<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="/dashboard" >
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>



                <li class="nav-item">
                    <a class="nav-link {{ (Request::url() !== url('/admin') && Request::url() !== url('/penggunas')) ? 'collapsed' : '' }}" data-bs-target="#User" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-menu-button-wide"></i><span>User</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                        <ul id="User" class="nav-content {{ (Request::url() !== url('/admin') && Request::url() !== url('/penggunas')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
                            <li>
                                <a href="/admin" class="{{ Request::url() == url('/admin') ? 'active' : '' }}">
                                    <i class="bi bi-circle"></i><span>Admin</span>
                                </a>
                            </li>
                            <li>
                                <a href="/penggunas" class="{{ Request::url() == url('/penggunas') ? 'active' : '' }}">
                                    <i class="bi bi-circle"></i><span>Pengguna</span>
                                </a>
                            </li>
                        </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link {{ (Request::url() !== url('/category') && Request::url() !== url('/product')) ? 'collapsed' : '' }}" data-bs-target="#Produk" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-menu-button-wide"></i><span>Produk</span><i class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="Produk" class="nav-content {{ (Request::url() !== url('/category') && Request::url() !== url('/product')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
                        <li>
                            <a href="/product" class="{{ Request::url() == url('/product') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Produk</span>
                            </a>
                        </li>
                        <li>
                            <a href="/category" class="{{ Request::url() == url('/category') ? 'active' : '' }}">
                                <i class="bi bi-circle"></i><span>Kategori</span>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-item">
                        <a class="nav-link {{ ( Request::url() !== url('/travel-package')) ? 'collapsed' : '' }}" data-bs-target="#paketWisata" data-bs-toggle="collapse" href="#">
                            <i class="bi bi-menu-button-wide"></i><span>Paket Wisata</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                            <ul id="paketWisata" class="nav-content {{ (Request::url() !== url('/travel-package')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
                                <li>
                                        <a href="/travel-package" class="{{ Request::url() == url('/travel-package') ? 'active' : '' }}">
                                            <i class="bi bi-circle"></i><span>Paket Wisata</span>
                                        </a>
                                </li>
                            </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ ( Request::url() !== url('/kategori-artikel') && Request::url() !== url('/artikel')) ? 'collapsed' : '' }}" data-bs-target="#kategori-artikel" data-bs-toggle="collapse" href="#">
                            <i class="bi bi-menu-button-wide"></i><span>Artikel</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                            <ul id="kategori-artikel" class="nav-content {{ (Request::url() !== url('/kategori-artikel') && Request::url() !== url('/artikel')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
                                <li>
                                        <a href="/kategori-artikel" class="{{ Request::url() == url('/kategori-artikel') ? 'active' : '' }}">
                                            <i class="bi bi-circle"></i><span>Kategori Artikel</span>
                                        </a>
                                </li>
                                <li>
                                        <a href="/artikel" class="{{ Request::url() == url('/artikel') ? 'active' : '' }}">
                                            <i class="bi bi-circle"></i><span>Artikel</span>
                                        </a>
                                </li>
                            </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ (Request::url() !== url('/orders') && Request::url() !== url('/reward')) ? 'collapsed' : '' }}" data-bs-target="#transaksi" data-bs-toggle="collapse" href="#">
                            <i class="bi bi-menu-button-wide"></i><span>Data Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <ul id="transaksi" class="nav-content {{ (Request::url() !== url('/orders') && Request::url() !== url('/reward')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
                            <li>
                                <a href="/orders" class="{{ Request::url() == url('/orders') ? 'active' : '' }}">
                                    <i class="bi bi-circle"></i><span>Orders</span>
                                </a>
                            </li>
                            <li>
                                <a href="/reward" class="{{ Request::url() == url('/reward') ? 'active' : '' }}">
                                    <i class="bi bi-circle"></i><span>Reward</span>
                                </a>
                            </li>
                        </ul>
                    </li>
</ul>
</aside>
