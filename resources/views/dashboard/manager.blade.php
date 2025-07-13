@extends('adminlte.layouts.app')
@section('title', 'Manager | Dashboard')
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
                    <!-- /.col-md-6 -->
                    <div class="col-lg-3">
                        <div class="card bg-danger">
                            <div class="card-body">
                                <i class="fas fa-wallet icon-top-right"></i>
                                <h5 class="card-title text-light">Total Pengeluaran</h5>

                                <h5 class="card-text font-weight-bold" id="totalExpenseText">Rp. 0</h5>
                                <a href="{{ route('index.expense') }}" class="card-link text-white">Lihat selengkapnya</a>

                            </div>
                        </div>


                    </div>
                    <!-- /.col-md-6 -->
                    <!-- /.col-md-6 -->
                    


                    </div>
                    <!-- Chart Section -->

                    <!-- /.col-md-6 -->
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header bg-dark">
                                <h3 class="card-title">Diagram Keuangan per 6 Bulan</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="incomeChart" style="width: 100%; height: 220px;"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header bg-dark">
                                <h3 class="card-title">Diagram Berdasarkan Kategori</h3>
                            </div>
                            <div class="card-body">
                                <canvas id="expenseCategoryChart" style="width: 100%; height: 90px;"></canvas>
                            </div>
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
    const totalExpense = {{ $totalExpense ?? 0 }};
    // Fungsi animasi angka (naik dari 0 ke targetValue)
    function animateNumber(elementId, targetValue, duration) {
        const element = document.getElementById(elementId);
        let currentValue = 0;
        const increment = targetValue / (duration / 20); // setiap 20ms

        const interval = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(interval);
            }
            element.textContent = `Rp. ${Math.floor(currentValue).toLocaleString('id-ID')}`;
        }, 20);
    }

    // Jalankan saat halaman selesai dimuat
    window.onload = function() {
        animateNumber('totalExpenseText', totalExpense, 1000);
    };
</script>

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

    // Create gradient for bars
    var gradientIncome = ctxBar.createLinearGradient(0, 0, 0, 400);
    gradientIncome.addColorStop(0, 'rgba(75, 192, 192, 1)');
    gradientIncome.addColorStop(1, 'rgba(75, 192, 192, 0.2)');

    var gradientExpense = ctxBar.createLinearGradient(0, 0, 0, 400);
    gradientExpense.addColorStop(0, 'rgba(255, 99, 132, 1)');
    gradientExpense.addColorStop(1, 'rgba(255, 99, 132, 0.2)');
    var incomeChart = new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: {!! json_encode($months) !!},
            datasets: [
                {
                    label: 'Pengeluaran',
                    data: {!! json_encode(array_values($expenseData)) !!},
                    backgroundColor: gradientExpense,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1,
                    barThickness: 30
                },
            ]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1000, // Durasi animasi
                easing: 'easeInOutQuart'
            },
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
                    {{ $totalExpense }}
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
                            var total = tooltipItem.dataset.data.reduce((sum, value) => sum + value, 0);
                            var currentValue = tooltipItem.raw;
                            var percentage = ((currentValue / total) * 100).toFixed(2);
                            return tooltipItem.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(currentValue) + ' (' + percentage + '%)';
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

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById('expenseCategoryChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($expenseCategoryLabels) !!},
                datasets: [{
                    label: 'Pengeluaran',
                    data: {!! json_encode($expenseCategoryValues) !!},
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', // warna awal
                        '#FF9F40', '#8BC34A', '#00ACC1', '#E91E63', '#F44336', // tambahan 5
                        '#9C27B0', '#3F51B5', '#CDDC39', '#009688', '#795548'  // tambahan 5 lagi
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                }
            }
        });
    });
</script>

@endpush