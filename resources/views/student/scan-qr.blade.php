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
        
        <button id="resetBtn" class="mt-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 w-full hidden">
            Scan Another QR Code
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
    const resetBtn = document.getElementById('resetBtn');
    const video = document.getElementById('qr-video');
    const placeholder = document.getElementById('scanPlaceholder');
    let html5QrCode = null;
    let stream = null;
    let isScanning = false;

    startScanBtn.addEventListener('click', async function() {
        startScanBtn.disabled = true;
        startScanBtn.textContent = 'Scanning...';
        placeholder.style.display = 'none';
        video.classList.remove('hidden');
        video.style.display = 'block';
        resetBtn.classList.add('hidden');

        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            video.srcObject = stream;
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

    resetBtn.addEventListener('click', function() {
        resetScan();
    });

    function startScanning() {
        const config = { fps: 15, qrbox: { width: 250, height: 250 }, verbose: false };
        html5QrCode = new Html5Qrcode('scanArea');
        isScanning = true;
        
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
        if (!isScanning) return;
        isScanning = false;

        stopScanning();

        let qrCode = null;
        try {
            const url = new URL(decodedText);
            qrCode = url.searchParams.get('qr');
        } catch (e) {
            qrCode = decodedText;
        }

        if (!qrCode) {
            showMessage('Invalid QR code format.', 'error');
            setTimeout(resetScan, 2000);
            return;
        }

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

                if (data.file_url) {
                    // --- التعديل الأساسي: فتح الملف تلقائياً بعد ثانية واحدة ليعطي وقتاً لرؤية رسالة النجاح ---
                    setTimeout(() => {
                        window.location.href = data.file_url;
                    }, 1000);

                    // تجهيز العرض في الصفحة تحسباً لرجوع الطالب
                    const fileUrl = data.file_url;
                    const fileName = fileUrl.split('/').pop().toLowerCase();
                    const fileExtension = fileName.split('.').pop();
                    
                    let fileEmbed = '';
                    if (fileExtension === 'pdf') {
                        fileEmbed = '<iframe src="' + fileUrl + '" class="w-full h-96 border-0 mt-4" style="min-height: 500px;"></iframe>';
                    } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                        fileEmbed = '<img src="' + fileUrl + '" class="max-w-full h-auto mt-4 mx-auto" style="max-height: 500px;">';
                    } else {
                        fileEmbed = '<div class="text-center mt-4"><a href="' + fileUrl + '" class="inline-block px-4 py-2 bg-purple-600 text-white rounded-md">Download File</a></div>';
                    }
                    
                    placeholder.innerHTML = '<div class="text-center p-4"><p class="text-green-500 text-lg font-medium mb-2">✓ Attendance marked!</p><p class="text-gray-600 text-sm mb-4">Opening: ' + data.lecture_title + '</p>' + fileEmbed + '</div>';
                } else {
                    placeholder.innerHTML = '<p class="text-green-500 text-lg font-medium">Attendance marked successfully!</p>';
                }
                
                placeholder.style.display = 'flex';
                startScanBtn.classList.add('hidden');
                resetBtn.classList.remove('hidden');
            } else {
                showMessage(data.message || 'Error marking attendance.', 'error');
                resetBtn.classList.remove('hidden');
                startScanBtn.classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Error marking attendance. Please try again.', 'error');
            setTimeout(resetScan, 2000);
        });
    }

    function onScanError(error) { /* Ignore */ }

    function stopScanning() {
        if (html5QrCode) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                html5QrCode = null;
            }).catch(err => console.log('Stop error:', err));
        }
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        isScanning = false;
    }

    function resetScan() {
        stopScanning();
        startScanBtn.disabled = false;
        startScanBtn.textContent = 'Start Scanning';
        startScanBtn.classList.remove('hidden');
        resetBtn.classList.add('hidden');
        video.classList.add('hidden');
        video.style.display = 'none';
        placeholder.innerHTML = '<div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-2"><svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg></div><p class="text-white text-lg font-medium">Ready to scan</p>';
        placeholder.style.display = 'flex';
    }

    function showMessage(message, type) {
        const existing = document.querySelector('.fixed.top-4');
        if (existing) existing.remove();
        const alertDiv = document.createElement('div');
        alertDiv.className = `fixed top-4 right-4 p-4 rounded-md shadow-lg z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
        alertDiv.textContent = message;
        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 5000);
    }
});
</script>
@endsection