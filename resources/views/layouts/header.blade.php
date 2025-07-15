<div class="site-wrapper">
    <!-- ======== 1.1. Header section ======== -->
    <header>
        <nav class="navbar navbar-expand-lg container pt-lg-4 pt-3 pb-lg-4 pb-3">
            <div class="container-fluid justify-content-lg-start justify-content-between">
                <!-- site logo -->
                <a class="nav-logo p-0" href="index-2.html">
                    <figure><img src="assets/images/logo.png" alt="logo"></figure>
                </a>
                <!-- navigation button  -->
                <div class="d-flex gap-3 flex-row-reverse">
                    <button class="open-aside" onclick="if (!window.__cfRLUnblockHandlers) return false; open_aside()"
                        type="button" data-cf-modified-e6a2bd62ac9a1c6d21597346-="">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <!-- navigation bar manu  -->
                    <div class="d-flex justify-content-between gap-xl-4 gap-lg-3 w-100 align-items-center">
                        <div class="collapse navbar-collapse " id="navbarSupportedContent">
                            <ul
                                class="navbar-nav d-flex justify-content-center align-items-center gap-lg-4 gap-md-3 gap-sm-2 gap-2 mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Accueil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"
                                        href="{{ route('about') }}">À propos de la banque</a>
                                </li>

                                <li class="dropdown">
                                    <a class="nav-link {{ request()->routeIs('credits') ? 'active' : '' }}" href="{{ route('credits') }}">Crédits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Support & Aide</a>
                                </li>
                            </ul>
                        </div>
                        <div class="search SearchModal">
                            <a href="#">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#" class="hover1 down-btn" id="getStartedAnchor">Espace Client</a>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div class="modal searchModal">
            <button class="close-btn d-flex"
                onclick="if (!window.__cfRLUnblockHandlers) return false; closeSearchModal()"
                data-cf-modified-e6a2bd62ac9a1c6d21597346-="">&times;</button>
            <div class="position-relative d-flex align-items-center justify-content-center ">
                <input type="text" placeholder="Search..." name="search">
                <button class="search-btn">
                    <img src="assets/images/icons/search.svg" alt="search">
                </button>
            </div>
        </div>
        <!--===== aside navigation slidebar =====-->
        <aside>
            <div id="mySidenav" class="right-sidbar">
                <div class="side-nav-logo d-flex justify-content-between align-items-center ps-2 pe-1 nav-logo">
                    <figure class="navbar-brand">
                        <a href="index-2.html">
                            <img src="assets/images/logo.svg" alt="Logo">
                        </a>
                    </figure>
                    <button class="fa-solid fa-xmark"
                        onclick="if (!window.__cfRLUnblockHandlers) return false; close_aside()"
                        data-cf-modified-e6a2bd62ac9a1c6d21597346-=""></button>
                </div>
                <ul class="pt-4">
                    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                        <a aria-current="page" href="index-2.html">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="About.html">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="Services.html">Services</a>
                    </li>
                    <li class="nav-item">
                        <a href="Contact.html">Contact</a>
                    </li>
                    <li class="nav-item">
                        <div class="d-flex align-items-center justify-content-between" id="slid-btn">
                            <button class="a-slid page-btn">Pages</button>
                            <i class="fa-solid fa-caret-down pe-2"></i>
                        </div>
                        <ul id="slid-drop">
                            <li><a href="Pricing.html">Pricing</a></li>
                            <li>
                                <a href="Testimonials.html">Testimonials</a>
                            </li>
                            <li><a href="Team.html">Team</a></li>
                            <li><a href="Blogs.html">Blogs</a></li>
                            <li>
                                <a href="BlogDetails.html">Blog Details</a>
                            </li>
                            <li><a href="Error.html">404</a></li>
                            <li>
                                <a href="ComingSoon.html">Coming Soon</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
    </header>