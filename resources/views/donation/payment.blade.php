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
<!-- Snap.js -->
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
            },
            onClose: function(){
                alert("Kamu menutup pembayaran sebelum selesai.");
            }
        });
    });
</script>
@endpush
