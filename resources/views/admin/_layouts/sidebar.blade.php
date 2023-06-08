<div class="sidebar-wrapper">
        <div>
                <div class="logo-wrapper"><a href="{{ route('dashboard') }}"><img class="img-fluid for-light" src="{{ url('assets/logo/logo-t.png') }}" alt="" style="height:35px;width:35px"><img class="img-fluid for-dark" src="{{ url('assets/logo/logo-t.png') }}" alt="" style="height:35px;width:35px"></a>
                        <div class="back-btn"><i class="fa fa-angle-left"></i></div>
                        <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="align-left"> </i></div>
                </div>
                <div class="logo-icon-wrapper"><a href="{{ route('dashboard') }}"><img class="img-fluid for-light" src="{{ url('assets/logo/logo.png') }}" alt="" style="height:35px;width:35px"><img class="img-fluid for-dark" src="{{ url('assets/logo/logo.png') }}" style="height:35px;width:35px" alt=""></a></div>
                <nav class="sidebar-main">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="sidebar-menu">
                                <ul class="sidebar-links" id="simple-bar">
                                        <li class="back-btn"><a href="{{ route('dashboard') }}"><img class="img-fluid for-light" src="{{ url('assets/logo/logo.png') }}" alt="" style="height:35px;width:35px"><img class="img-fluid for-dark" src="{{ url('assets/logo/logo.png') }}" style="height:35px;width:35px" alt=""></a>
                                                <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                                        </li>
                                        <li class="sidebar-main-title">
                                                <div>
                                                        <h4 class="lan-1">General </h4>
                                                </div>
                                        </li>
                                        <li class="sidebar-list"> <a class="sidebar-link sidebar-title link-nav @stack('dashboard')" href="{{ route('dashboard') }}"><i data-feather="home"></i><span class="lan-3">Dashboard</span></a></li>
                                        <li class="sidebar-main-title">
                                                <div>
                                                        <h4 class="lan-8">Store </h4>
                                                </div>
                                        </li>
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav @stack('wisatas')" href="{{ route('wisatas') }}"><i data-feather="coffee"> </i><span>Wisata</span></a></li>
                                        <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav @stack('sukas')" href="{{ route('sukas') }}"><i data-feather="heart"> </i><span>Suka</span></a></li>
                                </ul>
                        </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                </nav>
        </div>
</div>