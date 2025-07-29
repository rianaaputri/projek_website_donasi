@extends('layouts.app')

@section('title', 'Proses Pembayaran')

@section('content')
<div class="container py-5 text-center">
    <h2>Proses Pembayaran</h2>
    <p>Mohon tunggu, Anda akan diarahkan ke halaman pembayaran...</p>
</div>
@endsection

@section('scripts')
<script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result){
                window.location.href = "/donations/success";
            },
            onPending: function(result){
                window.location.href = "/donations/pending";
            },
            onError: function(result){
                alert('Pembayaran gagal: ' + result.status_message);
                window.location.href = "/donations/failed";
            },
            onClose: function(){
                alert('Transaksi dibatalkan.');
                window.location.href = "/donations/canceled";
            }
        });
    });
</script>
@endsection
