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
            background-color: #2F7BAC !important;style="color: #;"
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
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
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="nav-link">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge" id="notificationCount">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header" id="notificationHeader">Notifications</span>
                        <div id="notificationList">
                            <!-- Isi notifikasi akan ditambahkan secara dinamis di sini -->
                        </div>

                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <!-- User Profile Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <img src="{{ asset('storage/profile_pictures/' . auth()->user()->profile_picture) }}"
                            class="img-circle elevation-2" alt="User Image" style="width: 30px; height: 30px;">
                        <span>{{ auth()->user()->name }}</span>
                    </a>
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
                <img src="{{ asset('assets/dist/img/logo.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('index.income') }}" class="nav-link">
                                <i class="nav-icon fas fa-dollar-sign"></i>
                                <p>
                                    Pemasukan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('index.expense') }}" class="nav-link">
                                <i class="nav-icon fas fa-wallet"></i>
                                <p>
                                    Pengeluaran
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('index.debt') }}" class="nav-link">
                                <i class="nav-icon fas fa-credit-card"></i>
                                <p>
                                    Hutang
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('index.bill') }}" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice"></i>
                                <p>
                                    Tagihan
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('index.report') }}" class="nav-link">
                                <i class="nav-icon fas fa-info"></i>
                                <p>
                                    Laporan
                                </p>
                            </a>
                        </li>
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
