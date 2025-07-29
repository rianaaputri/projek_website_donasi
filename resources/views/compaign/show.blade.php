@extends('layouts.app')

@section('title', $campaign->title)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card mb-4">
                <img src="{{ asset('storage/' . $campaign->image) }}" class="card-img-top" alt="{{ $campaign->title }}">
                <div class="card-body">
                    <h2 class="card-title">{{ $campaign->title }}</h2>
                    <p class="card-text">{{ $campaign->description }}</p>

                   @php
    use Carbon\Carbon;

    $startDate = Carbon::parse($campaign->created_at)->startOfDay();
    $endDate = Carbon::parse($campaign->end_date)->endOfDay();
    $today = Carbon::today();

    $totalDays = $startDate->diffInDays($endDate) + 1;

    if ($today->lt($startDate)) {
        $daysRunning = 0;
    } elseif ($today->gt($endDate)) {
        $daysRunning = $totalDays;
    } else {
        $daysRunning = $startDate->diffInDays($today) + 1;
    }

    $daysPercentage = $totalDays > 0 ? round(($daysRunning / $totalDays) * 100, 1) : 0;

    $totalDonation = $campaign->donations->sum('amount');
    $donorCount = $campaign->donations->count();
    $progress = $campaign->target_amount > 0 ? ($totalDonation / $campaign->target_amount) * 100 : 0;
@endphp


                    <div class="mt-4">
                        <h5>Progress Donasi</h5>
                        <div class="progress mb-2">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ number_format($progress, 1) }}%
                            </div>
                        </div>
                        <p class="mb-1"><strong>Rp {{ number_format($totalDonation, 0, ',', '.') }}</strong> Terkumpul</p>
                        <p class="mb-1">Target: <strong>Rp {{ number_format($campaign->target_amount, 0, ',', '.') }}</strong></p>
                        <p class="mb-1">Donatur: <strong>{{ $donorCount }}</strong></p>
                       <div class="progress-info">
  <p><strong>{{ $daysRunning }}</strong> hari berjalan dari <strong>{{ $totalDays }}</strong> hari</p>
<p>Progress Waktu: {{ $daysPercentage }}%</p>

</div>
                    </div>

                    <div class="mt-4">
                        <h5>Bagikan Campaign:</h5>
                        <div class="d-flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" class="btn btn-primary btn-sm" target="_blank">Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($campaign->title) }}" class="btn btn-info btn-sm" target="_blank">Twitter</a>
                            <a href="https://wa.me/?text={{ urlencode($campaign->title . ' ' . request()->fullUrl()) }}" class="btn btn-success btn-sm" target="_blank">WhatsApp</a>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Donasi Form (opsional) -->
            <div class="card">
                <div class="card-body">
                    <h4>Donasi Sekarang</h4>
                    <form action="{{ route('donations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">

                        <div class="mb-3">
                            <label for="donor_name" class="form-label">Nama</label>
                            <input type="text" name="donor_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="donor_email" class="form-label">Email</label>
                            <input type="email" name="donor_email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="donor_phone" class="form-label">No. HP</label>
                            <input type="text" name="donor_phone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Jumlah Donasi</label>
                            <input type="number" name="amount" class="form-control" min="1000" required>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea name="message" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_anonymous" id="is_anonymous">
                            <label class="form-check-label" for="is_anonymous">
                                Donasi sebagai anonim
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Donasi Sekarang</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
