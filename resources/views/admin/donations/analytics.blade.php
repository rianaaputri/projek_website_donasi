@extends('layouts.admin')

@section('title', 'Analytics Donasi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analytics Donasi</h1>
        <a href="{{ route('admin.donations.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Monthly Statistics Chart -->
    <div class="row mb-4">
        <div class="col-xl-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Donasi Bulanan {{ date('Y') }}</h6>
                </div>
                <div class="card-body">
                    <canvas id="monthlyChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Status Distribution -->
        <div class="col-xl-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Campaigns -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Campaign dengan Donasi Terbanyak</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Campaign</th>
                                    <th>Kategori</th>
                                    <th>Total Donasi</th>
                                    <th>Jumlah Donatur</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topCampaigns as $index => $campaign)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('campaigns.show', $campaign->id) }}" 
                                               target="_blank" class="text-decoration-none">
                                                {{ Str::limit($campaign->title, 40) }}
                                            </a>
                                        </td>
                                        <td>{{ $campaign->category }}</td>
                                        <td class="text-success">
                                            <strong>Rp {{ number_format($campaign->total_donations ?? 0, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>{{ $campaign->donations_count ?? 0 }} donatur</td>
                                        <td>
                                            @if($campaign->status == 'active')
                                                <span class="badge badge-success">Aktif</span>
                                            @elseif($campaign->status == 'completed')
                                                <span class="badge badge-info">Selesai</span>
                                            @else
                                                <span class="badge badge-secondary">Tidak Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data campaign</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Large Donations -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Donasi Besar Terbaru</h6>
                    <small class="text-muted">(≥ Rp 100.000)</small>
                </div>
                <div class="card-body">
                    @forelse($largeDonations as $donation)
                        <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                            <div class="mr-3">
                                <div class="icon-circle bg-success">
                                    <i class="fas fa-hand-holding-heart text-white"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <div class="font-weight-bold">{{ $donation->donor_name }}</div>
                                <div class="text-success font-weight-bold">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </div>
                                <div class="small text-muted">
                                    {{ Str::limit($donation->campaign->title, 25) }}
                                </div>
                                <div class="small text-muted">
                                    {{ $donation->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Belum ada donasi besar</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Breakdown Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Rincian Donasi Bulanan {{ date('Y') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Bulan</th>
                            <th>Total Donasi</th>
                            <th>Total Jumlah</th>
                            <th>Rata-rata per Donasi</th>
                            <th>Donasi Terbesar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $monthNames = [
                                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                            ];
                        @endphp
                        
                        @forelse($monthlyStats as $stat)
                            @php
                                $maxDonation = \App\Models\Donation::where('status', 'success')
                                    ->whereMonth('created_at', $stat->month)
                                    ->whereYear('created_at', $stat->year)
                                    ->max('amount');
                                $avgDonation = $stat->total_count > 0 ? $stat->total_amount / $stat->total_count : 0;
                            @endphp
                            <tr>
                                <td>{{ $monthNames[$stat->month] }} {{ $stat->year }}</td>
                                <td>{{ $stat->total_count }} donasi</td>
                                <td class="text-success">
                                    <strong>Rp {{ number_format($stat->total_amount, 0, ',', '.') }}</strong>
                                </td>
                                <td>Rp {{ number_format($avgDonation, 0, ',', '.') }}</td>
                                <td class="text-info">
                                    <strong>Rp {{ number_format($maxDonation ?? 0, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Belum ada data donasi bulan ini</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Donations Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($monthlyStats->reverse() as $stat)
                '{{ $monthNames[$stat->month] }}',
            @endforeach
        ],
        datasets: [{
            label: 'Total Donasi (Rp)',
            data: [
                @foreach($monthlyStats->reverse() as $stat)
                    {{ $stat->total_amount }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Jumlah Donasi',
            data: [
                @foreach($monthlyStats->reverse() as $stat)
                    {{ $stat->total_count }},
                @endforeach
            ],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                title: {
                    display: true,
                    text: 'Total Donasi (Rp)'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                title: {
                    display: true,
                    text: 'Jumlah Donasi'
                },
                grid: {
                    drawOnChartArea: false,
                },
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Trend Donasi Bulanan'
            }
        }
    }
});

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
const statusChart = new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($statusStats as $status)
                '{{ ucfirst($status->status) }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($statusStats as $status)
                    {{ $status->count }},
                @endforeach
            ],
            backgroundColor: [
                '#28a745', // Success - Green
                '#ffc107', // Pending - Yellow
                '#dc3545'  // Failed - Red
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            title: {
                display: true,
                text: 'Status Donasi'
            }
        }
    }
});
</script>

<style>
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush