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
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = "{{ route('donation.success', $donation->id) }}";
            },
            onPending: function(result){
                window.location.href = "{{ route('donation.success', $donation->id) }}";
            },
            onError: function(result){
                alert("Pembayaran gagal atau dibatalkan.");
                // Anda mungkin ingin mengarahkan ke halaman lain,
                // atau setidaknya memuat ulang halaman untuk mencoba lagi
                // window.location.reload();
            },
            onClose: function(){
                alert("Kamu menutup pembayaran sebelum selesai.");
                // Anda mungkin ingin mengarahkan ke halaman status tertunda
                // atau ke halaman lain yang relevan setelah pembayaran ditutup
                // window.location.href = "{{ route('donation.status', $donation->id) }}";
            }
        });
    });
</script>
<script>
    function checkStatus() {
        // Ini adalah baris yang dikoreksi:
        // Menggunakan 'donation.status' sesuai dengan definisi rute di web.php
        fetch("{{ route('donation.status', $donation->id) }}")
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.href = "{{ route('donation.success', $donation->id) }}";
                } else {
                    // Cek lagi setelah 3 detik jika status bukan 'success'
                    setTimeout(checkStatus, 3000);
                }
            })
            .catch(error => {
                console.error('Error fetching status:', error);
                // Pertimbangkan penanganan error di sini, mungkin berhenti mencoba atau menampilkan pesan
                // alert("Terjadi kesalahan saat memeriksa status pembayaran.");
            });
    }

    // Mulai pengecekan setelah Snap dijalankan dan halaman dimuat sepenuhnya
    // Anda mungkin ingin menunda ini sedikit untuk memastikan Snap sudah diinisialisasi
    // atau hanya memanggilnya setelah ada interaksi user, tergantung alur aplikasi Anda.
    // Untuk tujuan koreksi route, ini sudah cukup.
    // Saya menunda sedikit agar ada waktu untuk Snap.js menginisialisasi.
    setTimeout(checkStatus, 5000);
</script>

@endpush