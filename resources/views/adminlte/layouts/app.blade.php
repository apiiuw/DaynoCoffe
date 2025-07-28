<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <link rel="icon" href="{{ asset('assetsLanding/img/1.png') }}" type="image/png">

    <script src="https://kit.fontawesome.com/d7833bfda5.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .card {
            position: relative;
        }

        .icon-top-right {
            position: absolute;
            top: 10px;
            right: 10px;
            color: white;
            font-size: 24px;
        }

        .main-sidebar {
            color: white;
            background-color:#343a40;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini" style="overflow-x: hidden !important;">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('home') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block" style="margin-left: 10px;">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
                <li class="nav-item dropdown" style="margin-left: 40px;">
                    <a class="nav-link" data-toggle="dropdown" href="#" role="button">
                        <i class="far fa-bell"></i>
                        @if(($dueDebts ?? collect())->count() + ($dueBills ?? collect())->count() > 0)
                            <span class="badge badge-danger navbar-badge">
                                {{ ($dueDebts ?? collect())->count() + ($dueBills ?? collect())->count() }}
                            </span>
                        @endif
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="width: 300px;">
                        <span class="dropdown-header">Notifikasi Tenggat Waktu</span>
                        <div class="dropdown-divider"></div>

                        @foreach($dueDebts ?? [] as $debt)
                            <a href="{{ route('index.debt') }}" class="dropdown-item text-sm">
                                <i class="fas fa-exclamation-circle mr-2 text-warning"></i>
                                Hutang: <strong>{{ $debt->category ?? 'Tidak diketahui' }}</strong><br>
                                <small class="text-muted ml-4">
                                    Jatuh tempo {{ \Carbon\Carbon::parse($debt->due_date)->diffForHumans() }}
                                </small>
                            </a>
                        @endforeach

                        @foreach($dueBills ?? [] as $bill)
                            <a href="{{ route('index.bill') }}" class="dropdown-item text-sm">
                                <i class="fas fa-exclamation-circle mr-2 text-info"></i>
                                Tagihan: <strong>{{ $bill->category ?? 'Tidak diketahui' }}</strong><br>
                                <small class="text-muted ml-4">
                                    Jatuh tempo {{ \Carbon\Carbon::parse($bill->due_date)->diffForHumans() }}
                                </small>
                            </a>
                        @endforeach

                        @if(($dueDebts ?? collect())->isEmpty() && ($dueBills ?? collect())->isEmpty())
                            <span class="dropdown-item text-sm text-muted">Tidak ada tenggat waktu dalam 7 hari</span>
                        @endif

                        <div class="dropdown-divider"></div>
                        <a href="{{ route('index.debt') }}" class="dropdown-item dropdown-footer">Lihat Semua Hutang</a>
                        <a href="{{ route('index.bill') }}" class="dropdown-item dropdown-footer">Lihat Semua Tagihan</a>
                    </div>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- User Profile Dropdown Menu -->
                <li class="nav-item dropdown">
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('logout') }}" class="dropdown-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->


        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="brand-link">
                <span class="brand-text font-weight-light">Dayno Kopi</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                  
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                
                {{-- DASHBOARD (semua role) --}}
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p style="color: white;">Dashboard</p>
                    </a>
                </li>

                {{-- ROLE: KASIR --}}
                @if(auth()->user()->role === 'kasir')
                    <li class="nav-item">
                        <a href="{{ route('index.income') }}" class="nav-link">
                            <i class="nav-icon fas fa-dollar-sign"></i>
                            <p style="color: white;">Pemasukan</p>
                        </a>
                    </li>
                @endif

                {{-- ROLE: MANAGER --}}
                @if(auth()->user()->role === 'manager')
                    <li class="nav-item">
                        <a href="{{ route('index.expense') }}" class="nav-link">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p style="color: white;">Pengeluaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manage-expanses.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-layer-group"></i>
                            <p style="color: white;">Kelola Kategori</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manage-menu.index') }}" class="nav-link">
                            <i class="nav-icon fa-solid fa-clipboard-list"></i>
                            <p style="color: white;">Kelola Menu</p>
                        </a>
                    </li>
                @endif

                {{-- ROLE: OWNER --}}
                @if(auth()->user()->role === 'owner')
                    <li class="nav-item">
                        <a href="{{ route('index.debt') }}" class="nav-link">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p style="color: white;">Hutang</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('index.bill') }}" class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p style="color: white;">Tagihan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('index.report') }}" class="nav-link">
                            <i class="nav-icon fas fa-info"></i>
                            <p style="color: white;">Laporan</p>
                        </a>
                    </li>
                @endif

            </ul>
        </nav>

                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')

        <!-- REQUIRED SCRIPTS -->
        <!-- Bootstrap 4 -->
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
        {{-- Notifikasi Tagihan --}}
        <script>
            $(document).ready(function() {
                function loadNotifications() {
                    // Panggil endpoint untuk mendapatkan tagihan jatuh tempo hari ini
                    $.ajax({
                        url: '/due-bills',
                        method: 'GET',
                        success: function(response) {
                            response.forEach(function(bill) {
                                var notificationItem = $('<a>')
                                    .addClass('dropdown-item')
                                    .attr('href', '/bill')
                                    .html(`Tagihan anda hari ini: ${bill.category}`);

                                $('#notificationList').prepend(notificationItem);
                            });

                            // Update jumlah notifikasi
                            updateNotificationCount();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching bill notifications:', error);
                        }
                    });

                    // Panggil endpoint untuk mendapatkan hutang jatuh tempo hari ini
                    $.ajax({
                        url: '/due-debts',
                        method: 'GET',
                        success: function(response) {
                            response.forEach(function(debt) {
                                var notificationItem = $('<a>')
                                    .addClass('dropdown-item')
                                    .attr('href', '/debt')
                                    .html(`Hutang anda hari ini: ${debt.debt_type}`);

                                $('#notificationList').prepend(notificationItem);
                            });

                            // Update jumlah notifikasi
                            updateNotificationCount();
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching debt notifications:', error);
                        }
                    });
                }

                function updateNotificationCount() {
                    var count = $('#notificationList .dropdown-item').length;
                    $('#notificationCount').text(count);
                    $('#notificationHeader').text(`You have ${count} notifications`);
                }

                // Load notifications on page load
                loadNotifications();
            });
        </script>
        </script>
        <!-- Sweet Alert -->
        @include('sweetalert::alert')
        @stack('scripts')
</body>

</html>
