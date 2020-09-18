<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>


  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-address-card"></i>
      </a>
      <div class="dropdown-menu dropdown-menu dropdown-menu-right">
        <!-- <a href="#" class="dropdown-item">
          <i class="fas fa-user"></i> Profile
        </a>
        <div class="dropdown-divider"></div> -->
        <a href="{{route('admin.logout')}}" class="dropdown-item">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->