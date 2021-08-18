<?php 
$path = Request::route()->action['prefix'];
$namePath = Request::route()->getName();
$sentinel = Sentinel::getUser();
?>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>
        </nav>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="{{ url('dashboard') }}" class="brand-link">
                <img src="{{  asset('img/pako/PAKO group 4-crop.png') }}" alt="Pako Logo" class="brand-image img-circle elevation-3" style="opacity: .8;margin-left: 0;">
                <span class="brand-text font-weight-light">{{ env('PROJECT_NAME') }} CMS</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="#" class="d-block">{{ $sentinel->fullname }}</a>
                </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ url('dashboard') }}" class="nav-link @if ($path === "/dashboard") active @endif">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('banner') }}" class="nav-link @if ($path === "/banner") active @endif">
                                <i class="far ion-android-image nav-icon"></i>
                                <p>Banner</p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview @if ($path === "/wheel") menu-open @endif">
                            <a href="#" class="nav-link @if ($path === "/wheel") active @endif">
                                <i class="far ion-model-s nav-icon"></i>
                                <p>Wheel</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10%">
                                <li class="nav-item">
                                    <a href="{{ url('/wheel/pako') }}" class="nav-link @if ($namePath === "pako") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pako</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/wheel/inko') }}" class="nav-link @if ($namePath === "inko") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Inko</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/wheel/fortis') }}" class="nav-link @if ($namePath === "fortis") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Fortis</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ url('/wheel/avantech') }}" class="nav-link @if ($namePath === "avantech") active @endif">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Avantech</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="far ion-images nav-icon"></i>
                                <p>Gallery</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10%">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Wheel</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Vehicle</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far ion-social-rss nav-icon"></i>
                                <p>Blog</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far ion-tshirt nav-icon"></i>
                                <p>Apparel</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10%">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Hoodie</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Hat</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>T-shirt</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Polo shirt</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far ion-compass nav-icon"></i>
                                <p>Contact</p>
                                <i class="fas fa-angle-left right"></i>
                            </a>
                            <ul class="nav nav-treeview" style="padding-left: 10%">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Dealer</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('admin') }}" class="nav-link @if ($path === "/admin") active @endif">
                                <i class="far ion-ios-people nav-icon"></i>
                                <p>Admin</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('logout') }}" class="nav-link @if ($path === "/admin") active @endif">
                                <i class="far ion-android-exit nav-icon"></i>
                                <p>Logout</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">@yield('pages')</h1>
                        </div>
                        @yield('content-helper')
                    </div>      
                </div>
            </div>
            <section class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </section>
        </div>
        <footer class="main-footer">
            <strong>PAKO CMS</strong>
        </footer>

        <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    @include('sweetalert::alert')
</body>