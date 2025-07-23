@extends('adminlte.layouts.app')

@section('title', 'Uangku | Halaman Pengeluaran')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Daftar Pengeluaran</h1>
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
                                    @if (!(request('month') || request('year') || request('day')))
                                        <a href="{{ route('create.expense') }}" class="btn btn-success">Tambah Pengeluaran</a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('index.expense') }}" class="form-inline mb-3">
                                    <div class="form-group mr-2">
                                        <label for="day" class="mr-2">Hari</label>
                                        <select name="day" id="day" class="form-control">
                                            <option value="">Belum disetel</option>
                                            @for ($d = 1; $d <= 31; $d++)
                                                <option value="{{ $d }}" {{ request('day') == $d ? 'selected' : '' }}>{{ $d }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="month" class="mr-2">Bulan</label>
                                        <select name="month" id="month" class="form-control">
                                            <option value="">Belum disetel</option>
                                            @for ($m = 1; $m <= 12; $m++)
                                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="form-group mr-2">
                                        <label for="year" class="mr-2">Tahun</label>
                                        <select name="year" id="year" class="form-control">
                                            <option value="">Belum disetel</option>
                                            @foreach ($availableYears as $year)
                                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger">Setel Periode</button>
                                    <a href="{{ route('index.expense') }}" class="btn btn-secondary ml-2">Reset Periode</a>
                                </form>

                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>ID Pengeluaran</th>
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'date', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}" class="text-danger">
                                                    Tanggal
                                                    @if (request()->query('field') == 'date')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up text-danger"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-danger"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-danger"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Harga Satuan</th>
                                            <th>Jumlah</th>
                                            <th>Harga Keseluruhan</th>
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'amount', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}" class="text-danger">
                                                    Total Harga
                                                    @if (request()->query('field') == 'amount')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up text-danger"></i>
                                                        @else
                                                            <i class="fas fa-sort-down text-danger"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort text-danger"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expensesGrouped as $groupedExpenses)
                                            @php
                                                $firstExpenses = $groupedExpenses->first();
                                            @endphp

                                            @foreach ($groupedExpenses as $expenses) <!-- Loop untuk setiap item dalam grup -->
                                                <tr>
                                                    @if ($loop->first) <!-- Hanya untuk baris pertama di grup -->
                                                        <td rowspan="{{ $groupedExpenses->count() }}">{{ $loop->parent->iteration }}</td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}">#{{ $firstExpenses->id_expenses }}</td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}">{{ date('d-m-Y', strtotime($firstExpenses->date)) }}</td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}"> <!-- Kolom kategori diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedExpenses as $innerExpenses)
                                                                <p>{{ $innerExpenses->category }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}"> <!-- Kolom deskripsi diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedExpenses as $innerExpenses)
                                                                <p>{{ $innerExpenses->description }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}"> <!-- Kolom harga satuan diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedExpenses as $innerExpennses)
                                                                <p>Rp {{ number_format($innerExpennses->price, 0, ',', '.') }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}"> <!-- Kolom jumlah diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedExpenses as $innerExpenses)
                                                                <p>{{ $innerExpenses->quantity }}</p>
                                                            @endforeach
                                                        </td>
                                                        <td rowspan="{{ $groupedExpenses->count() }}"> <!-- Kolom harga keseluruhan diulang untuk setiap entri dalam grup -->
                                                            @foreach ($groupedExpenses as $innerExpenses)
                                                                <p>Rp {{ number_format($innerExpenses->total_price, 0, ',', '.') }}</p>
                                                            @endforeach
                                                        </td>
                                                    @endif

                                                    <!-- Kolom Total Harga dan Aksi hanya ditampilkan di baris pertama -->
                                                    @if ($loop->first)
                                                        <td rowspan="{{ $groupedExpenses->count() }}">Rp {{ number_format($firstExpenses->amount, 0, ',', '.') }}</td>
                                                        {{-- <td rowspan="{{ $groupedExpenses->count() }}">
                                                            <a href="{{ route('edit.expense', $firstExpenses->id_expenses) }}" class="btn btn-warning btn-sm">Edit</a>
                                                        </td> --}}
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>

                                </table>

                            </div>

                            <div class="card-footer">
                                <p>Total Pengeluaran Keseluruhan: Rp {{ number_format($totalExpenses, 0, ',', '.') }}</p>
                                <div class="d-flex justify-content-center">
                                    {{ $rawData->links('pagination::bootstrap-4') }}
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
                        <h3 class="card-title">Diagram Pengeluaran per Bulan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="viewSelector">Tampilan Data:</label>
                            <select id="viewSelector" class="form-control" style="max-width: 200px;">
                                <option value="monthly" selected>Per Bulan</option>
                                <option value="daily">Per Hari</option>
                            </select>
                        </div>
                        <canvas id="expensesChart"></canvas>
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
    const ctx = document.getElementById('expensesChart').getContext('2d');

    // Mengambil data dari controller untuk monthly dan daily
    const monthlyLabels = {!! json_encode($months) !!};
    const monthlyData = {!! json_encode($monthlyData) !!};

    const dailyLabels = {!! json_encode($daily['labels']) !!};  // Use daily data for labels
    const dailyData = {!! json_encode($daily['values']) !!};  // Use daily data for values

    let expensesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyLabels,  // Menampilkan bulan sebagai label
            datasets: [{
                label: 'Pengeluaran Bulanan', // Label untuk data bulanan
                data: monthlyData, // Data untuk chart bulanan
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 1)',
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
            expensesChart.data.labels = dailyLabels; // Label harian
            expensesChart.data.datasets[0].data = dailyData; // Data harian
            expensesChart.data.datasets[0].label = 'Pengeluaran Harian'; // Label chart harian
        } else {
            expensesChart.data.labels = monthlyLabels; // Label bulanan
            expensesChart.data.datasets[0].data = monthlyData; // Data bulanan
            expensesChart.data.datasets[0].label = 'Pengeluaran Bulanan'; // Label chart bulanan
        }
        expensesChart.update(); // Update chart
    });
});

    </script>

@endpush
