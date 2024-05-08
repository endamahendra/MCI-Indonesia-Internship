<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="/dashboard" >
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li>
    <li class="nav-item">
        <a class="nav-link {{ (Request::url() !== url('/product') && Request::url() !== url('/category') && Request::url() !== url('/travel-package') && Request::url() !== url('/admin')&& Request::url() !== url('/penggunas')) ? 'collapsed' : '' }}" data-bs-target="#master" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Master Data</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="master" class="nav-content {{ (Request::url() !== url('/product') && Request::url() !== url('/category') && Request::url() !== url('/travel-package') && Request::url() !== url('/admin')&& Request::url() !== url('/penggunas')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/product" class="{{ Request::url() == url('/product') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Produk</span>
            </a>
          </li>
          <li>
              <a href="/category" class="{{ Request::url() == url('/category') ? 'active' : '' }}">
                <i class="bi bi-circle"></i><span>Kategori Produk</span>
            </a>
        </li>
    </li>
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
    <li>
      <a href="/travel-package" class="{{ Request::url() == url('/travel-package') ? 'active' : '' }}">
        <i class="bi bi-circle"></i><span>Paket Wisata</span>
      </a>
    </li>
</ul>
</li>

     <li class="nav-item">
        <a class="nav-link {{ (Request::url() !== url('/orders')) ? 'collapsed' : '' }}" data-bs-target="#transaksi" data-bs-toggle="collapse" href="#">
          <i class="bi bi-menu-button-wide"></i><span>Data Transaksi</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="transaksi" class="nav-content {{ (Request::url() !== url('/orders')) ? 'collapse' : '' }}" data-bs-parent="#sidebar-nav">
          <li>
            <a href="/orders" class="{{ Request::url() == url('/orders') ? 'active' : '' }}">
              <i class="bi bi-circle"></i><span>Orders</span>
            </a>
          </li>
        </ul>
    </li>

</ul>
</aside>
