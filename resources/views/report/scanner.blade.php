@extends('report.base')

@section('content')

    <video muted autoplay playsinline id="qr-video"></video>

@endsection

@section('codispeu')
    <script src="{{ asset('js/admin/vendor/qr-scanner.min.js') }}"></script>
    <script>
        var video = document.getElementById('qr-video');
        var scanner = new QrScanner(video, function(result) {
            
        });
        scanner.start();
    </script>
@endsection