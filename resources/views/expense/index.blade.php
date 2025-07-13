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
                                    <a href="{{ route('create.expense') }}" class="btn btn-success">Tambah Pengeluaran</a>
                                </div>
                            </div>
                            <div class="card-body">
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
                    <th>manager</th>
                @endif

                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expenses as $expense)
                <tr>
                    <td>{{ $loop->iteration + ($expenses->currentPage() - 1) * $expenses->perPage() }}</td>
                    <td>{{ date('d-m-Y', strtotime($expense->date)) }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>Rp {{ number_format($expense->amount, 0, ',', '.') }}</td>
                    <td>{{ $expense->description }}</td>

                    @if(auth()->user()->role === 'owner')
                        <td>{{ $expense->user->name ?? '-' }}</td>
                    @endif

                    <td>
                        <a href="{{ route('edit.expense', $expense->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $expense->id }})">Delete</button>
                        <form id="delete-form-{{ $expense->id }}" action="{{ route('delete.expense', $expense->id) }}" method="POST" style="display: none;">
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
    <p>Total Pengeluaran: Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
    <div class="d-flex justify-content-center">
        {{ $expenses->links('pagination::bootstrap-4') }}
    </div>
</div>

                <!-- Chart Section -->
               <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Diagram Pengeluaran</h3>
            </div>
            <div class="card-body">
                <div class="form-group mb-3">
                    <label for="expenseViewSelector">Tampilan Data:</label>
                    <select id="expenseViewSelector" class="form-control" style="max-width: 200px;">
                        <option value="monthly" selected>Per Bulan</option>
                        <option value="daily">Per Hari</option>
                    </select>
                </div>
                <canvas id="expenseChart"></canvas>
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
document.addEventListener("DOMContentLoaded", function() {
    const monthlyLabels = {!! json_encode($months) !!};
    const monthlyData = {!! json_encode($expenseData->values()) !!};
    const dailyLabels = {!! json_encode($dailyExpenseLabels) !!};
    const dailyData = {!! json_encode($dailyExpenseValues) !!};

    const ctx = document.getElementById('expenseChart').getContext('2d');
    let expenseChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Pengeluaran',
                data: monthlyData,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Toggle view
    document.getElementById('expenseViewSelector').addEventListener('change', function () {
        if (this.value === 'daily') {
            expenseChart.data.labels = dailyLabels;
            expenseChart.data.datasets[0].data = dailyData;
            expenseChart.data.datasets[0].label = 'Pengeluaran Harian';
        } else {
            expenseChart.data.labels = monthlyLabels;
            expenseChart.data.datasets[0].data = monthlyData;
            expenseChart.data.datasets[0].label = 'Pengeluaran Bulanan';
        }
        expenseChart.update();
    });
});
</script>


@endpush
