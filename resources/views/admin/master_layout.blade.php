<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Tú Phương </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset("assets/plugins/fontawesome-free/css/all.min.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset("assets/dist/css/adminlte.min.css")}}">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link href="{{asset("assets/plugins/toastr/toastr.min.css")}}" rel="stylesheet" />
  <link href="{{asset("assets/plugins/toastr/toastr.css")}}" rel="stylesheet" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
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


        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-bell"></i>
            <span class="badge badge-warning navbar-badge" id="notification-count">0</span>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">
              <span id="total-alerts-text">0 Thông báo hạn sử dụng</span>
            </span>
            <div class="dropdown-divider"></div>

            <!-- Expired Batches Section -->
            <div id="expired-section" style="display: none;">
              <span class="dropdown-header px-3 py-2" style="font-size: 12px; color: #dc3545;">
                <i class="fas fa-exclamation-circle"></i> LÔ ĐÃ HẾT HẠN
              </span>
              <div id="expired-list" class="px-2">
                <!-- Expired items will be loaded here -->
              </div>
              <div class="dropdown-divider"></div>
            </div>

            <!-- Expiring Soon Section -->
            <div id="expiring-section" style="display: none;">
              <span class="dropdown-header px-3 py-2" style="font-size: 12px; color: #ffc107;">
                <i class="fas fa-clock"></i> LÔ SẮP HẾT HẠN
              </span>
              <div id="expiring-list" class="px-2">
                <!-- Expiring items will be loaded here -->
              </div>
              <div class="dropdown-divider"></div>
            </div>

            <a href="{{ route('admin_inventory') }}" class="dropdown-item dropdown-footer">
              <i class="fas fa-cube"></i> Xem Chi Tiết Quản Lý Kho
            </a>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{route('trang_chu')}}" class="brand-link elevation-4">
        <img src="{{asset("assets/dist/img/AdminLTELogo.png")}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">TuPhuong</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            @php
            $img = Auth::user()->image ?? 'uploads/no_image.png';
            @endphp
            <img src="{{asset($img)}}" class="img-circle elevation-2" alt="User Image">
          </div>
          <div class="info">
            <a href="{{route('profile')}}" class="d-block">{{ Auth::user()->name  }}</a>
            <a href="{{ route('logout') }}" class="d-block text-muted small" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Đăng xuất">
              <i class="fas fa-sign-out-alt"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa fa-solid fa-heart"></i>
                <p>
                  Quản lý sản phẩm
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/admin/list_product" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Thuốc</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../charts/flot.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Thực phẩm chức năng</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../charts/inline.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Inline</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="../charts/uplot.html" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>uPlot</p>
                  </a>
                </li>
              </ul>
            </li>

            <!-- Đơn hàng -->
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-shopping-cart"></i>
                <p>
                  Quản lý đơn hàng
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('order.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tạo đơn hàng</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/orders" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách đơn hàng</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-tree"></i>
                <p>
                  Quản lý danh mục
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="/admin/list_category" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Thể loại</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/list_brand" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Thương hiệu</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/list_banner" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Banner</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="/admin/list_news" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Bài viết</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-edit"></i>
                <p>
                  Tài khoản khách hàng
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('list_user') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Danh sách tài khoản </p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('create_user')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Thêm mới tài khoản</p>
                  </a>
                </li>

              </ul>
            </li>


            <li class="nav-header">LABELS</li>
            <li class="nav-item">
              <a href="{{route('admin_inventory')}}" class="nav-link">
                <i class="nav-icon far fa-circle text-danger"></i>
                <p class="text">Quản lý kho</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-warning"></i>
                <p>Warning</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon far fa-circle text-info"></i>
                <p>Informational</p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->

    @yield('page_content')
    @include('admin.chatbot-admin')

    <!-- /.content-wrapper -->



    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
  <!-- ./wrapper -->

  <!-- jQuery -->
  <script src="{{asset("assets/plugins/jquery/jquery.min.js")}}"></script>
  <!-- Bootstrap 4 -->
  <script src="{{asset("assets/plugins/bootstrap/js/bootstrap.bundle.min.js")}}"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
  <!-- AdminLTE App -->
  <script src="{{asset("assets/dist/js/adminlte.min.js")}}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{asset("assets/dist/js/demo.js")}}"></script>
  <script src="https://cdn.datatables.net/2.1.2/js/dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/2.1.2/js/dataTables.bootstrap5.min.js"></script>
  <script src="{{asset("assets/plugins/toastr/toastr.min.js")}}"></script>

  <script>
    // Load expiry notifications
    function loadExpiryNotifications() {
      fetch('{{ route("inventory.expiry-notifications") }}')
        .then(response => response.json())
        .then(data => {
          const totalAlerts = data.total_alerts;
          const expiredCount = data.expired.count;
          const expiringCount = data.expiring.count;

          // Update notification badge
          document.getElementById('notification-count').textContent = totalAlerts > 0 ? totalAlerts : '0';

          // Update total alerts text
          const alertText = totalAlerts === 0 ?
            'Không có thông báo hạn sử dụng' :
            `${totalAlerts} Thông báo hạn sử dụng`;
          document.getElementById('total-alerts-text').textContent = alertText;

          // Display expired batches
          if (expiredCount > 0) {
            document.getElementById('expired-section').style.display = 'block';
            let expiredHtml = '';
            data.expired.batches.forEach(batch => {
              expiredHtml += `
                <a href="{{ route('admin_inventory_history') }}?status=expired" class="dropdown-item">
                  <div class="row">
                    <div class="col-8">
                      <i class="fas fa-times-circle text-danger"></i>
                      <strong>${batch.product.name}</strong><br>
                      <small>Lô: ${batch.code}</small>
                    </div>
                    <div class="col-4 text-right">
                      <span class="badge badge-danger">Hết hạn</span>
                    </div>
                  </div>
                </a>
              `;
            });
            document.getElementById('expired-list').innerHTML = expiredHtml;
          }

          // Display expiring batches
          if (expiringCount > 0) {
            document.getElementById('expiring-section').style.display = 'block';
            let expiringHtml = '';
            data.expiring.batches.forEach(batch => {
              const daysUntilExpiry = Math.ceil((new Date(batch.date_end) - new Date()) / (1000 * 60 * 60 * 24));
              expiringHtml += `
                <a href="{{ route('admin_inventory_history') }}?status=expiring" class="dropdown-item">
                  <div class="row">
                    <div class="col-8">
                      <i class="fas fa-exclamation-triangle text-warning"></i>
                      <strong>${batch.product.name}</strong><br>
                      <small>Lô: ${batch.code} | ${daysUntilExpiry} ngày</small>
                    </div>
                    <div class="col-4 text-right">
                      <span class="badge badge-warning">Sắp hết</span>
                    </div>
                  </div>
                </a>
              `;
            });
            document.getElementById('expiring-list').innerHTML = expiringHtml;
          }
        })
        .catch(error => console.error('Error loading notifications:', error));
    }

    // Load notifications on page load
    document.addEventListener('DOMContentLoaded', function() {
      loadExpiryNotifications();
      // Refresh notifications every 5 minutes
      setInterval(loadExpiryNotifications, 5 * 60 * 1000);
    });
  </script>

  @stack('scripts')
  @yield('scriptss')

</body>

</html>