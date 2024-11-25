@extends('adminlte.layouts.app')
@section('title', 'Uangku | Dashboard')
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="card bg-info">
                            <div class="card-body">
                                <i class="fas fa-dollar-sign icon-top-right"></i>
                                <h5 class="card-title text-light">Total Pemasukan</h5>

                                <h5 class="card-text font-weight-bold">
                                    Rp. {{ number_format($totalIncome, 0, ',', '.') }}
                                </h5>

                                <a href="{{ route('index.income') }}" class="card-link text-white">Lihat selengkapnya </a>

                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-lg-3">
                        <div class="card bg-danger">
                            <div class="card-body">
                                <i class="fas fa-wallet icon-top-right"></i>
                                <h5 class="card-title text-light">Total Pengeluaran</h5>

                                <h5 class="card-text font-weight-bold">
                                    Rp. {{ number_format($totalExpense, 0, ',', '.') }}
                                </h5>

                                <a href="{{ route('index.expense') }}" class="card-link text-white">Lihat selengkapnya</a>

                            </div>
                        </div>


                    </div>
                    <!-- /.col-md-6 -->
                    <!-- /.col-md-6 -->
                    <div class="col-lg-3">
                        <div class="card bg-primary">
                            <div class="card-body">
                                <i class="fas fa-credit-card icon-top-right"></i>
                                <h5 class="card-title text-light">Total Hutang</h5>

                                <h5 class="card-text font-weight-bold">
                                    Rp. {{ number_format($totalDebt, 0, ',', '.') }}
                                </h5>

                                <a href="{{ route('index.debt') }}" class="card-link text-white">Lihat selengkapnya</a>

                            </div>
                        </div>


                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-lg-3">
                        <div class="card bg-secondary">
                            <div class="card-body">
                                <i class="fas fa-file-invoice icon-top-right"></i>
                                <h5 class="card-title text-light">Total Tagihan</h5>

                                <h5 class="card-text font-weight-bold tex-white">
                                    Rp {{ number_format($totalBill, 0, ',', '.') }}
                                </h5>

                                <a href="{{ route('index.bill') }}" class="card-link text-white">Lihat selengkapnya</a>

                            </div>
                        </div>


                    </div>
                    <!-- Chart Section -->

                    <!-- /.col-md-6 -->
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header bg-dark">
                                <h3 class="card-title">Diagram Keuangan per Bulan</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="incomeChart" style="width: 100%; height: 220px;"></canvas>
                            </div>


                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header bg-dark">
                                <h3 class="card-title">Pie Chart Total Keuangan</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="totalChart" style="width: 100%; height: 90px;"></canvas>
                            </div>
                        </div>
                    </div>

                </div>


                <!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
@push('scripts')
    <script>
        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Bar Chart for monthly data
            var ctxBar = document.getElementById('incomeChart').getContext('2d');
            var incomeChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [{
                            label: 'Pemasukan',
                            data: {!! json_encode(array_values($incomeData)) !!},
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Pengeluaran',
                            data: {!! json_encode(array_values($expenseData)) !!},
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Hutang',
                            data: {!! json_encode(array_values($debtData)) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Tagihan',
                            data: {!! json_encode(array_values($billData)) !!},
                            backgroundColor: 'rgba(255, 206, 86, 0.2)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Pie Chart for total amounts
            var ctxPie = document.getElementById('totalChart').getContext('2d');
            var totalChart = new Chart(ctxPie, {
                type: 'pie',
                data: {
                    labels: ['Pemasukan', 'Pengeluaran', 'Hutang', 'Tagihan'],
                    datasets: [{
                        label: 'Total Nominal',
                        data: [
                            {{ $totalIncome }},
                            {{ $totalExpense }},
                            {{ $totalDebt }},
                            {{ $totalBill }}
                        ],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    var total = tooltipItem.dataset.data.reduce((sum, value) => sum +
                                        value, 0);
                                    var currentValue = tooltipItem.raw;
                                    var percentage = ((currentValue / total) * 100).toFixed(2);
                                    return tooltipItem.label + ': Rp ' + new Intl.NumberFormat('id-ID')
                                        .format(currentValue) + ' (' + percentage + '%)';
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value, ctx) => {
                                let sum = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = (value * 100 / sum).toFixed(2) + "%";
                                return percentage;
                            },
                            color: 'dark',
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });
        });
    </script>
@endpush
