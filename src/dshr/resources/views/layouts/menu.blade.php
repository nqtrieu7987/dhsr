<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block pt-1">
        <a href="{{Request::url()}}" class="link-home">{{isset($site) ? $site : 'Home'}}</a>
      </li>
      @if(isset($link_url))
        <a @if($link_url['icon'] =='fa fa-reply') onclick="goBack()" @else href="{{$link_url['url']}}" @endif class="nav-link text-primary ml-3" style="font-weight: bold; font-size: 20px;margin-top: -3px;"><i class="{{$link_url['icon']}}" aria-hidden="true"></i> {{$link_url['title']}}</a>
      @endif
    </ul>

    <!-- SEARCH FORM -->
    {{-- <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form> --}}

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->
      {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ url('dist/img/user1-128x128.jpg') }}" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Brad Diesel
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">Call me whenever you can...</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ url('dist/img/user8-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  John Pierce
                  <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">I got your message bro</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="{{ url('dist/img/user3-128x128.jpg') }}" alt="User Avatar" class="img-size-50 img-circle mr-3">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  Nora Silvester
                  <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm">The subject goes here</p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
              </div>
            </div>
            <!-- Message End -->
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li> --}}
      <!-- Notifications Dropdown Menu -->
      {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
          <i class="fas fa-th-large"></i>
        </a>
      </li> --}}

      <li class="nav-item dropdown">
        {{-- <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <span class="badge badge-danger navbar-badge">3</span>
        </a> --}}
        <a class="nav-link user-menu" data-toggle="dropdown" href="#">
          @if(Auth::check())
            <div class="header-user">
                <img src="{{ Auth::user()->workPassPhoto }}">
            </div>
          @endif
            {{-- {{ Auth::user()->name }}<span class="caret"></span> --}}
        </a>
        {{-- <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            @if(Auth::check())
              <div class="dropdown-item">
                  <img class="avatar" src="{{ Auth::user()->workPassPhoto }}">
                  <b>&nbsp;admin</b>
              </div>
              <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  <img class="icon-menu-left" src="{{ url('/images/logout_icon.png') }}"/>&nbsp;&nbsp;{{ __('Logout') }}
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            @else
              <a class="dropdown-item" href="{{ route('login') }}">
                  <i class="fa fa-user"></i>&nbsp;&nbsp;Login
              </a>
              <a class="dropdown-item" href="{{ route('register') }}">
                  <i class="fa fa-user"></i>&nbsp;&nbsp;Register
              </a>
            @endif
        </div> --}}
      </li>
    </ul>
</nav>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="{{ url('images/logo1.png') }}" alt="AdminLTE Logo" class="brand-image elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light"><b class="font-weight-bold">DS</b> Human <b class="font-weight-bold">Resource</b></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          {{-- <li class="nav-item has-treeview {{ (Request::is('all-jobs') || Request::is('job') || Request::is('job-type')) ? 'menu-open' : null }}">
            <a href="#" class="nav-link {{ (Request::is('all-jobs') || Request::is('job') || Request::is('job-type')) ? 'active' : null }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Job manager
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{route('job.all-jobs')}}" class="nav-link {{ (Request::is('all-jobs')) ? 'active' : null }}">
                  <i class="far fa-circle nav-icon text-danger"></i>
                  <p>All Jobs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('job.index')}}" class="nav-link {{ (Request::is('job')) ? 'active' : null }}">
                  <i class="far fa-circle nav-icon text-warning"></i>
                  <p>Job</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{route('job-type.index')}}" class="nav-link {{ (Request::is('job-type')) ? 'active' : null }}">
                  <i class="far fa-circle nav-icon text-success"></i>
                  <p>Job Type</p>
                </a>
              </li>
            </ul>
          </li> --}}
          
          <li class="nav-item">
            <a href="/" class="nav-link {{ (Request::is('/')) ? 'active' : null }}">
              <img class="icon-menu-left" src="{{ url('/images/home.png') }}"/>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('job.index')}}" class="nav-link {{ (Request::is('job')) ? 'active' : null }}">
              <img class="icon-menu-left" src="{{ url('/images/job_sidebar_icon.png') }}"/>
              <p>
                Job
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('job-type.index')}}" class="nav-link {{ (Request::is('job-type')) ? 'active' : null }}">
              <img class="icon-menu-left" src="{{ url('/images/jobtype_sidebar_icon.png') }}"/>
              <p>
                Job Type
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('user.approvalAttire')}}" class="nav-link @if(isset($site) && $site == 'approval-attire') active @endif">
              <img class="icon-menu-left" src="{{ url('/images/approval-icon.png') }}"/>
              <p>Approval Attire</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('users')}}" class="nav-link @if(isset($site) && $site == 'Users') active @endif">
              <img class="icon-menu-left" src="{{ url('/images/users_sidebar_icon.png') }}"/>
              <p>Users</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('job.all-jobs')}}" class="nav-link {{ (Request::is('all-jobs')) ? 'active' : null }}">
              <img class="icon-menu-left" src="{{ url('/images/alljob_sidebar_icon.png') }}"/>
              <p>
                Attendance
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('clocking')}}" class="nav-link {{in_array(Route::currentRouteName(), ['clocking']) ? 'active' : ''}}">
              <img class="icon-menu-left" src="{{ url('/images/clocking_sidebar_icon.png') }}"/>
              <p>
                Attendance report
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('hotel.index')}}" class="nav-link {{in_array(Route::currentRouteName(), ['hotel.index']) ? 'active' : ''}}">
              <img class="icon-menu-left" src="{{ url('/images/hotel_sidebar_icon.png') }}"/>
              <p>
                Hotel
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('report.job')}}" class="nav-link {{in_array(Route::currentRouteName(), ['report.job']) ? 'active' : ''}}">
              <img class="icon-menu-left" src="{{ url('/images/hotelattendance_sidebar_icon.png') }}"/>
              <p>
                Hotel Attendance
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{{route('view-type.index')}}" class="nav-link {{in_array(Route::currentRouteName(), ['view-type.index']) ? 'active' : ''}}">
              <img class="icon-menu-left" src="{{ url('/images/register_sidebar_icon.png') }}"/>
              <p>
                View Type
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              <img class="icon-menu-left" src="{{ url('/images/summary_sidebar_icon.png') }}"/>
              <p>
                Summary
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <img class="icon-menu-left" src="{{ url('/images/register_sidebar_icon.png') }}"/>
              <p>
                Register
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>
                Attendance
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <img class="icon-menu-left" src="{{ url('/images/wallet_sidebar_icon.png') }}"/>
              <p>
                Wallet
              </p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="{{url('/bank')}}" class="nav-link {{in_array(Route::currentRouteName(), ['bank']) ? 'active' : ''}}">
              <i class="nav-icon fas fa-bank"></i>
              <p>
                Bank
              </p>
            </a>
          </li>
          <li class="nav-item">
             <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                  <i style="font-size: 22px" class="fas fa-sign-out-alt"></i>
                  <p>{{ __('Logout') }}</p>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>