@extends('layouts.student-app')

@section('title', 'Scan QR - Attendance')

@section('content')

    
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="section-header">
            <h1 class="section-title">Scan QR Code</h1>
            <p class="section-subtitle">Mark your attendance by scanning the lecture QR code with your camera</p>
        </div>

        <!-- Main Scanner Card -->
        <div class="max-w-7xl mx-auto">
            <div class="hall-card rounded-3xl p-8 shadow-2xl border border-purple-200/50 backdrop-blur-sm">
                <div class="text-center mb-8">
                    <div id="scanArea" class="mx-auto w-full max-w-[470px] aspect-square bg-black rounded-3xl relative mb-8 shadow-2xl overflow-hidden border-2 border-white/10">
    
    <video id="qr-video" class="w-full h-full object-cover hidden" autoplay playsinline></video>
    
    <div class="absolute inset-0 pointer-events-none border-[20px] border-transparent">
        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-purple-500 rounded-tl-lg"></div>
        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-purple-500 rounded-tr-lg"></div>
        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-purple-500 rounded-bl-lg"></div>
        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-purple-500 rounded-br-lg"></div>
    </div>

    <div id="scanPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/80 backdrop-blur-sm">
        <div class="p-4 bg-white/10 rounded-2xl mb-4">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-white text-xl font-bold mb-1">Ready to Scan</p>
                            <p class="text-white/80 text-sm">Point camera at QR code</p>
                        </div>
                        <canvas id="qr-canvas" class="hidden"></canvas>
                    </div>
                    
                    <button id="startScan" class="group inline-flex justify-center items-center py-4 px-8 border border-transparent shadow-xl text-lg font-bold rounded-2xl text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-4 focus:ring-purple-500/50 transition-all duration-300 w-full h-14 transform hover:scale-[1.02] active:scale-[0.98]">
                        <svg class="w-6 h-6 mr-3 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        Start Camera
                    </button>
                    
                    <button id="resetBtn" class="mt-4 inline-flex justify-center items-center py-3 px-6 border border-gray-300 shadow-lg text-sm font-semibold rounded-xl text-gray-700 bg-white/80 backdrop-blur-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all duration-300 w-full h-12 hidden group hover:shadow-xl">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16c.55 0 1 .45 1 1V5c0 .55-.45 1-.55 1H20v11c0 .55-.45 1-1 1H4c-.55 0-1-.45-1-1V6c0-.55.45-1 1-1h.54C3.45 6 3 5.55 3 5V5c0-.55.45-1 1-1zM8 8h8v8H8z"/>
                        </svg>
                        Scan Another QR
                    </button>
                </div>

                <!-- Instructions -->
                <div class="border-t border-gray-200/50 pt-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center justify-center">
                        <svg class="w-8 h-8 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        How it works
                    </h2>
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="flex items-start space-x-4 p-4 hall-card rounded-xl hover:shadow-lg transition-all">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">1</div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">Point Camera</h3>
                                <p class="text-gray-600">Hold steady at the professor's QR code</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-4 hall-card rounded-xl hover:shadow-lg transition-all">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">2</div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">Auto Detect</h3>
                                <p class="text-gray-600">Scanner detects and reads automatically</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4 p-4 hall-card rounded-xl hover:shadow-lg transition-all">
                            <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg">3</div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">Marked!</h3>
                                <p class="text-gray-600">Attendance recorded + materials unlocked</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        startScanBtn.innerHTML = '<svg class="w-6 h-6 mr-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" pathLength="1" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>Scanning...';
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
        const config = { fps: 15, qrbox: { width: 280, height: 280 }, verbose: false };
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
                    setTimeout(() => {
                        window.location.href = data.file_url;
                    }, 1000);

                    const fileUrl = data.file_url;
                    const fileName = fileUrl.split('/').pop().toLowerCase();
                    const fileExtension = fileName.split('.').pop();
                    
                    let fileEmbed = '';
                    if (fileExtension === 'pdf') {
                        fileEmbed = '<iframe src="' + fileUrl + '" class="w-full h-96 border-0 mt-4 rounded-xl shadow-lg" style="min-height: 500px;"></iframe>';
                    } else if (['jpg','jpeg','png','gif','webp'].includes(fileExtension)) {
                        fileEmbed = '<img src="' + fileUrl + '" class="max-w-full h-auto mt-4 mx-auto rounded-xl shadow-lg" style="max-height: 500px;">';
                    } else {
                        fileEmbed = '<div class="text-center mt-4"><a href="' + fileUrl + '" class="inline-flex px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl font-bold shadow-lg hover:shadow-xl transition-all">Download Materials</a></div>';
                    }
                    
                    placeholder.innerHTML = '<div class="text-center p-8"><div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl"><svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><p class="text-2xl font-bold text-green-400 mb-2">✓ Success!</p><p class="text-white/90 text-lg mb-4">' + data.lecture_title + '</p>' + fileEmbed + '</div>';
                } else {
                    placeholder.innerHTML = '<div class="text-center p-8"><div class="w-16 h-16 bg-green-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl"><svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div><p class="text-2xl font-bold text-green-400">Attendance Marked!</p><p class="text-white/90 text-lg">You can now return to your schedule</p></div>';
                }
                
                placeholder.style.display = 'flex';
                startScanBtn.classList.add('hidden');
                resetBtn.classList.remove('hidden');
            } else {
                showMessage(data.message || 'Error marking attendance.', 'error');
                startScanBtn.classList.add('hidden');
                resetBtn.classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('Network error. Please try again.', 'error');
            setTimeout(resetScan, 2000);
        });
    }

    function onScanError(error) { }

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
        startScanBtn.innerHTML = '<svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>Start Camera';
        startScanBtn.classList.remove('hidden');
        resetBtn.classList.add('hidden');
        video.classList.add('hidden');
        video.style.display = 'none';
        placeholder.innerHTML = '<div class="w-20 h-20 bg-white/20 rounded-2xl flex items-center justify-center mb-4 border-4 border-white/30"><svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div><p class="text-white text-xl font-bold mb-1">Ready to Scan</p><p class="text-white/80 text-sm">Point camera at QR code</p>';
        placeholder.style.display = 'flex';
    }

    function showMessage(message, type) {
        const existing = document.querySelector('.toast-message');
        if (existing) existing.remove();
        const toast = document.createElement('div');
        toast.className = `toast-message fixed top-20 right-6 p-6 rounded-2xl shadow-2xl z-50 text-white font-bold text-lg backdrop-blur-sm transform translate-x-full animate-slide-in ${type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('animate-slide-out');
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }
});
</script>
@endsection
