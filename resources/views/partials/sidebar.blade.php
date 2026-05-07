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
                
                <li class="nav-item" {{ Request::is('scan.barcode') ? 'active' : '' }}>
                <a class="nav-link" href="{{ route('scan.barcode') }}">
                    <span class="menu-title">Scan Barcode</span>
                    <i class="mdi mdi-barcode-scan menu-icon"></i>
                </a>
                </li>

                <li class="nav-item" {{ Request::is('adminvendor.index') ? 'active' : '' }}>
                <a class="nav-link" href="{{ route('adminvendor.index') }}">
                    <span class="menu-title">Vendor</span>
                    <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                </a>
                </li>

                <li class="nav-item" {{ Request::is('customer.index') ? 'active' : '' }}>
                <a class="nav-link" href="{{ route('customer.index') }}">
                    <span class="menu-title">Customer</span>
                    <i class="mdi mdi-format-list-bulleted menu-icon"></i>
                </a>
                </li>

                <!-- modul 4 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#modul4"
                    aria-expanded="false" aria-controls="modul4">
                        <span class="menu-title">Modul 4</span>
                        <i class="menu-arrow"></i>
                        <i class="mdi mdi-table menu-icon"></i>
                    </a>

                    <div class="collapse" id="modul4">
                        <ul class="nav flex-column sub-menu">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('tabel.index') }}">
                                    Tabel
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('datatables.index') }}">
                                    Datatables
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('select.index') }}">
                                    Select
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('select2.index') }}">
                                    Select 2
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- modul 5 -->
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="collapse" href="#modul5"
                    aria-expanded="false" aria-controls="modul5">
                        <span class="menu-title">Modul 5</span>
                        <i class="menu-arrow"></i>
                        <i class="mdi mdi-lightning-bolt menu-icon"></i>
                    </a>

                    <div class="collapse" id="modul5">
                        <ul class="nav flex-column sub-menu">

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ajax.wilayah') }}">
                                    Ajax Wilayah
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ajax.kasir') }}">
                                    Ajax Kasir
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('axios.wilayah') }}">
                                    Axios Wilayah
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('axios.kasir') }}">
                                    Axios Kasir
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

            </ul>
</nav>