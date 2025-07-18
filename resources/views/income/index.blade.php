@extends('adminlte.layouts.app')

@section('title', 'Dayno Kopi | Halaman Pemasukan')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Pemasukan</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="text-right">
                                    <a href="{{ route('create.income') }}" class="btn btn-success">Tambah Pemasukan</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('index.income') }}" class="form-inline mb-3">
                                    <div class="form-group mr-2">
                                        <label for="month" class="mr-2">Bulan</label>
                                        <select name="month" id="month" class="form-control">
                                            <option value="">-- Semua --</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="year" class="mr-2">Tahun</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">-- Semua --</option>
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('index.income') }}" class="btn btn-secondary ml-2">Reset</a>
                                </form>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Pemasukan</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Harga Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Harga Keseluruhan</th>
                                            <th>Total Harga</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incomesGrouped as $groupedIncomes)
                                            @php
                                                $firstIncome = $groupedIncomes->first();
                                            @endphp

                                            @foreach ($groupedIncomes as $income) <!-- Loop untuk setiap item dalam grup -->
                                                <tr>
                                                    @if ($loop->first) <!-- Hanya untuk baris pertama di grup -->
                                                        <td rowspan="{{ $groupedIncomes->count() }}">{{ $loop->parent->iteration }}</td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}">#{{ $firstIncome->id_incomes }}</td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}">{{ date('d-m-Y', strtotime($firstIncome->date)) }}</td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}"> <!-- Kolom kategori diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedIncomes as $innerIncome)
                                                                <p>{{ $innerIncome->category }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}"> <!-- Kolom deskripsi diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedIncomes as $innerIncome)
                                                                <p>{{ $innerIncome->description }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}"> <!-- Kolom harga satuan diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedIncomes as $innerIncome)
                                                                <p>Rp {{ number_format($innerIncome->price, 0, ',', '.') }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}"> <!-- Kolom jumlah diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedIncomes as $innerIncome)
                                                                <p>{{ $innerIncome->quantity }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}"> <!-- Kolom harga keseluruhan diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedIncomes as $innerIncome)
                                                                <p>Rp {{ number_format($innerIncome->total_price, 0, ',', '.') }}</p>
                                                            @endforeach
                                                        </td>
                                                    @endif

                                                    <!-- Kolom Total Harga dan Aksi hanya ditampilkan di baris pertama -->
                                                    @if ($loop->first)
                                                        <td rowspan="{{ $groupedIncomes->count() }}">Rp {{ number_format($firstIncome->amount, 0, ',', '.') }}</td>
                                                        <td rowspan="{{ $groupedIncomes->count() }}">
                                                            <a href="{{ route('edit.income', $firstIncome->id_incomes) }}" class="btn btn-warning btn-sm">Edit</a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="card-footer">
                                <p>Total Pemasukan Keseluruhan: Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                                <div class="d-flex justify-content-center">
                                    {{ $incomes->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chart Section -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Diagram Pemasukan per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="viewSelector">Tampilan Data:</label>
                            <select id="viewSelector" class="form-control" style="max-width: 200px;">
                                <option value="monthly" selected>Per Bulan</option>
                                <option value="daily">Per Hari</option>
                            </select>
                        </div>
                        <canvas id="incomeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Delete Data!',
                text: 'Anda yakin ingin menghapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Tidak, batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Error!',
                text: '{{ session('error') }}',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('incomeChart').getContext('2d');

        // Mengambil data dari controller untuk monthly dan daily
        const monthlyLabels = {!! json_encode($months) !!};
        const monthlyData = {!! json_encode($monthlyData) !!};

        const dailyLabels = {!! json_encode($daily['labels']) !!};
        const dailyData = {!! json_encode($daily['values']) !!};

        console.log("Monthly Data: ", monthlyData);
        console.log("Daily Data: ", dailyData);

        let incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels, // Menampilkan bulan sebagai label
                datasets: [{
                    label: 'Pemasukan Bulanan', // Label untuk data bulanan
                    data: monthlyData, // Data untuk chart bulanan
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Event listener untuk memilih tampilan data bulanan atau harian
        document.getElementById('viewSelector').addEventListener('change', function () {
            const selected = this.value;
            if (selected === 'daily') {
                incomeChart.data.labels = dailyLabels; // Label harian
                incomeChart.data.datasets[0].data = dailyData; // Data harian
                incomeChart.data.datasets[0].label = 'Pemasukan Harian'; // Label chart harian
            } else {
                incomeChart.data.labels = monthlyLabels; // Label bulanan
                incomeChart.data.datasets[0].data = monthlyData; // Data bulanan
                incomeChart.data.datasets[0].label = 'Pemasukan Bulanan'; // Label chart bulanan
            }
            incomeChart.update(); // Update chart
        });
    });
    </script>

@endpush
