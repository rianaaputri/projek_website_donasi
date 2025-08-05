@extends('layouts.app')

@section('title', 'Pembayaran Donasi')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <h4>Proses Pembayaran Donasi</h4>
        <p>Mohon tunggu, kamu akan diarahkan ke halaman pembayaran...</p>
    </div>
</div>
@endsection

@push('scripts')
<!-- ✅ Gunakan config() bukan env() di Blade -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ✅ Jalankan Snap
    snap.pay('{{ $snapToken }}', {
        onSuccess: function(result){
            window.location.href = "{{ route('donation.success', $donation->id) }}";
        },
        onPending: function(result){
            window.location.href = "{{ route('donation.success', $donation->id) }}";
        },
        onError: function(result){
            alert("Pembayaran gagal atau dibatalkan.");
            window.location.href = "{{ route('donation.status', $donation->id) }}";
        },
        onClose: function(){
            alert("Kamu menutup pembayaran sebelum selesai.");
            window.location.href = "{{ route('donation.status', $donation->id) }}";
        }
    });

    // ✅ Cek status setiap 5 detik
    setTimeout(checkStatus, 5000);
});

// ✅ Fungsi pengecekan status (auto polling)
function checkStatus() {
    fetch("{{ route('donation.status', $donation->id) }}")
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                window.location.href = "{{ route('donation.success', $donation->id) }}";
            } else {
                setTimeout(checkStatus, 5000); // Cek ulang setiap 5 detik
            }
        })
        .catch(error => {
            console.error('Gagal memeriksa status:', error);
        });
}
</script>
@endpush
