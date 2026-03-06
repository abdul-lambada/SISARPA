@extends('layouts.app')

@section('title', 'Analisis Biaya Pemeliharaan')
@section('header', 'Cost-Benefit Analysis & Maintenance Forecast')

@section('content')
<div class="row">
    <!-- Tren Biaya Bulanan -->
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Tren Pengeluaran Servis (6 Bulan Terakhir)</h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="monthlyChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Biaya Per Kategori -->
    <div class="col-md-4">
        <div class="card card-outline card-danger">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Biaya per Kategori</h3>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Top Assets with High Maintenance (Potential Replacement) -->
    <div class="col-md-12">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle mr-1"></i> Analisis Aset "High-Maintenance" (Saran Penggantian)</h3>
            </div>
            <div class="card-body">
                <p class="text-muted small">Aset di bawah ini memiliki akumulasi biaya servis yang tinggi. Pertimbangkan untuk membeli unit baru jika biaya servis mendekati harga beli baru.</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-warning">
                            <tr>
                                <th>Nama Barang</th>
                                <th>Kode Barang</th>
                                <th class="text-center">Total Frekuensi Servis</th>
                                <th class="text-center">Total Biaya Akumulasi</th>
                                <th>Rekomendasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topAssets as $asset)
                            <tr>
                                <td>{{ $asset->barang->nama_barang }}</td>
                                <td><code>{{ $asset->barang->kode_barang }}</code></td>
                                <td class="text-center"><span class="badge badge-warning">{{ $asset->jumlah_servis }} Kali</span></td>
                                <td class="text-center text-bold text-danger">Rp {{ number_format($asset->total_biaya, 0, ',', '.') }}</td>
                                <td>
                                    @if($asset->total_biaya > 1000000 || $asset->jumlah_servis >= 3)
                                        <span class="text-danger"><i class="fas fa-arrow-up"></i> <strong>Tinggi:</strong> Pertimbangkan Penggantian Baru</span>
                                    @else
                                        <span class="text-success"><i class="fas fa-check"></i> Masih Layak Maintenance</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($topAssets->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data pemeliharaan yang tercatat.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(function () {
        // Data Tren Bulanan
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyCosts->pluck('month')) !!},
                datasets: [{
                    label: 'Total Biaya (Rp)',
                    data: {!! json_encode($monthlyCosts->pluck('total')) !!},
                    borderColor: 'rgba(60,141,188,0.8)',
                    backgroundColor: 'rgba(60,141,188,0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // Data Per Kategori
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($categoryCosts->pluck('nama_kategori')) !!},
                datasets: [{
                    data: {!! json_encode($categoryCosts->pluck('total')) !!},
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    });
</script>
@endpush
