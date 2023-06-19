@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div id="reader" class="col-lg-6">

        </div>
    </div>
@endsection
@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        window.location.href = "{{route('admin.orders.create', ['user' => ''])}}" + decodedText;
    }

    document.addEventListener("DOMContentLoaded", function(event) {
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10 },
            false
        );

        html5QrcodeScanner.render(onScanSuccess);
    });
</script>
@endsection
