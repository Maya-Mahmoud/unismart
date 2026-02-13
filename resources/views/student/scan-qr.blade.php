@extends('layouts.student-app')

@section('title', 'Scan QR Code')

@section('content')
<div class="max-w-md mx-auto mt-8 p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Scan QR Code to mark attendance</h1>
    
    <div class="text-center mb-6">
        <div id="scanArea" class="mx-auto w-64 h-64 bg-black rounded-lg relative mb-4">
            <video id="qr-video" class="w-full h-full rounded-lg object-cover hidden"></video>
            <div id="scanPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-2">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-white text-lg font-medium">Ready to scan</p>
            </div>
            <canvas id="qr-canvas" class="hidden"></canvas>
        </div>
        
        <button id="startScan" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full">
            Start Scanning
        </button>
    </div>
    
    <div class="border-t pt-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-3">How to mark attendance</h2>
        <ol class="space-y-2 text-sm text-gray-600">
            <li class="flex items-start">
                <span class="mr-2 font-medium">1.</span>
                <span>Point your camera at the QR code</span>
            </li>
            <li class="flex items-start">
                <span class="mr-2 font-medium">2.</span>
                <span>Hold steady</span>
            </li>
            <li class="flex items-start">
                <span class="mr-2 font-medium">3.</span>
                <span>Wait for confirmation</span>
            </li>
        </ol>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startScanBtn = document.getElementById('startScan');
    const video = document.getElementById('qr-video');
    const placeholder = document.getElementById('scanPlaceholder');
    const canvas = document.getElementById('qr-canvas');
    const scanArea = document.getElementById('scanArea');
    let html5QrCode;
    let stream;

    startScanBtn.addEventListener('click', async function() {
        startScanBtn.disabled = true;
        startScanBtn.textContent = 'Scanning...';
        placeholder.style.display = 'none';
        video.classList.remove('hidden');
        video.style.display = 'block';

        try {
            // Start camera
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;

            // Wait for video to load
            video.onloadedmetadata = () => {
                video.play();
                startScanning();
            };
        } catch (err) {
            console.error('Camera access denied', err);
            showMessage('Camera access denied. Please allow camera permissions.', 'error');
            resetScan();
        }
    });

    function startScanning() {
        const config = { fps: 15, qrbox: { width: 250, height: 250 }, verbose: false };
        html5QrCode = new Html5Qrcode('scanArea');
        html5QrCode.start(
            { facingMode: 'environment' },
            config,
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error('Unable to start scanning', err);
            showMessage('Unable to start scanning. Please try again.', 'error');
            resetScan();
        });
    }

    function onScanSuccess(decodedText, decodedResult) {
        // Stop scanning
        stopScanning();

        // Parse QR
        const url = new URL(decodedText);
        const qrCode = url.searchParams.get('qr');

        if (!qrCode) {
            showMessage('Invalid QR code format.', 'error');
            resetScan();
            return;
        }

        // Mark attendance
        fetch('{{ route("student.scan-qr.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ qr_code: qrCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage(data.message, 'success');
                video.style.display = 'none';
                placeholder.innerHTML = '<p class="text-white text-lg font-medium">Attendance marked!</p>';
                placeholder.style.display = 'flex';
            } else {
                showMessage(data.message, 'error');
                resetScan();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error marking attendance. Please try again.', 'error');
            resetScan();
        });
    }

    function onScanError(error) {
        // Ignore scan errors during continuous scanning - no logging to avoid spam
    }

    function stopScanning() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
            }).catch(err => console.error('Error stopping scanner:', err));
        }
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    function resetScan() {
        stopScanning();
        startScanBtn.disabled = false;
        startScanBtn.textContent = 'Start Scanning';
        video.classList.add('hidden');
        video.style.display = 'none';
        placeholder.innerHTML = '<div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-2"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div><p class="text-white text-lg font-medium">Ready to scan</p>';
        placeholder.style.display = 'flex';
    }

    function showMessage(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }

    // Cleanup on page unload
    window.addEventListener('beforeunload', stopScanning);
});
</script>
@endsection
