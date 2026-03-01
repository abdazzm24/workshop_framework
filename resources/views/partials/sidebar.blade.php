<nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">

                <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}">
                    <span class="login-status online"></span>
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
                    <span class="text-secondary text-small">Administrator</span>
                    </div>
                </a>
                </li>

                <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
                </li>

                <li class="nav-item" {{ Request::is('buku.index') ? 'active' : '' }}>
                <a class="nav-link" href="{{ route('buku.index') }}">
                    <span class="menu-title">Buku</span>
                    <i class="mdi mdi-book menu-icon"></i>
                </a>
                </li>

                <li class="nav-item" {{ Request::is('kategori.index') ? 'active' : '' }}>
                <a class="nav-link" href="{{ route('kategori.index') }}">
                    <span class="menu-title">Kategori</span>
                    <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                </a>
                </li>

                <li class="nav-item" {{ Request::is('barang.index') ? 'active' : '' }}>
                <a class="nav-link" href="{{ route('barang.index') }}">
                    <span class="menu-title">Barang</span>
                    <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                </a>
                </li>

            </ul>
</nav>