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
                                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                                </option>
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
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'date', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
                                                    Tanggal
                                                    @if (request()->query('field') == 'date')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up"></i>
                                                        @elseif(request()->query('sort') == 'desc')
                                                            <i class="fas fa-sort-down"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Kategori</th>
                                            <th>
                                                <a href="{{ request()->fullUrlWithQuery(['field' => 'amount', 'sort' => request()->query('sort') == 'asc' ? 'desc' : 'asc']) }}">
                                                    Jumlah
                                                    @if (request()->query('field') == 'amount')
                                                        @if (request()->query('sort') == 'asc')
                                                            <i class="fas fa-sort-up"></i>
                                                        @elseif(request()->query('sort') == 'desc')
                                                            <i class="fas fa-sort-down"></i>
                                                        @endif
                                                    @else
                                                        <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Deskripsi</th>

                                            @if(auth()->user()->role === 'owner')
                                                <th>Nama Kasir</th>
                                            @endif

                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($incomes as $income)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-m-Y', strtotime($income->date)) }}</td>
                                                <td>{{ $income->category }}</td>
                                                <td>Rp {{ number_format($income->amount, 0, ',', '.') }}</td>
                                                <td>{{ $income->description }}</td>

                                                @if(auth()->user()->role === 'owner')
                                                    <td>{{ $income->user->name ?? 'Tidak diketahui' }}</td>
                                                @endif

                                                <td>
                                                    <a href="{{ route('edit.income', $income->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $income->id }})">Delete</button>
                                                    <form id="delete-form-{{ $income->id }}" action="{{ route('delete.income', $income->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- /.card-body -->
                            <div class="card-footer">
                                <p>Total Pemasukan: Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                                <div class="d-flex justify-content-center">
                                    {{ $incomes->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
        </section>
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

        const monthlyLabels = {!! json_encode($months) !!};
        const monthlyData = {!! json_encode($incomeData->values()) !!};

        const dailyLabels = {!! json_encode($daily['labels']) !!};
        const dailyData = {!! json_encode($daily['values']) !!};

        let incomeChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Pemasukan Bulanan',
                    data: monthlyData,
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

        document.getElementById('viewSelector').addEventListener('change', function () {
            const selected = this.value;
            if (selected === 'daily') {
                incomeChart.data.labels = dailyLabels;
                incomeChart.data.datasets[0].data = dailyData;
                incomeChart.data.datasets[0].label = 'Pemasukan Harian';
            } else {
                incomeChart.data.labels = monthlyLabels;
                incomeChart.data.datasets[0].data = monthlyData;
                incomeChart.data.datasets[0].label = 'Pemasukan Bulanan';
            }
            incomeChart.update();
        });
    });
</script>


@endpush
