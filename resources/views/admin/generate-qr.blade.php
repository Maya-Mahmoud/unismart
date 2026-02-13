<x-admin-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
    
<div class="section-header">
            <h1 class="section-title">Generate Attendance QR Code</h1>
            <p class="section-subtitle">Create time-limited QR codes for student attendance</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 hall-card">
            <!-- Lecture selection and generate button -->
            <div class=" p-6  rounded-lg shadow hall-card">
                <label for="lectureSelect" class="block font-medium mb-2">Select Lecture</label>
                <select id="lectureSelect" class="w-full border border-gray-300 rounded-md p-2 mb-4 text-black">
                    <option value="">Select a lecture</option>
                </select>
                <button id="generateQrBtn" class="text-white w-full bg-gradient-to-r from-purple-500 to-blue-600  py-2 rounded-md disabled:opacity-50" disabled>
    <svg class="inline w-5 h-5 mr-2 animate-spin hidden" id="loadingSpinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
    </svg>
    Generate QR Code
</button>
                <div id="lectureInfo" class="mt-4 p-3 bg-blue-50 rounded hidden text-black">
                    <p><strong id="infoTitle"></strong></p>
                    <p>Professor: <span id="infoProfessor"></span></p>
                    <p>Hall: <span id="infoHall"></span></p>
                </div>
            </div>

            <!-- QR Code display -->
            <div class=" p-6 rounded-lg shadow flex flex-col items-center justify-center">
                <h2 class="font-semibold mb-4 text-xl">Attendance QR Code</h2>
                <div id="qrCodeContainer" class=" border border-gray-900 rounded p-4 w-96 h-96 flex items-center justify-center text-gray-400">
                    Select a lecture and generate QR code
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const lectureSelect = document.getElementById('lectureSelect');
            const generateBtn = document.getElementById('generateQrBtn');
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            const lectureInfo = document.getElementById('lectureInfo');
            const infoTitle = document.getElementById('infoTitle');
            const infoProfessor = document.getElementById('infoProfessor');
            const infoHall = document.getElementById('infoHall');
            const loadingSpinner = document.getElementById('loadingSpinner');

            // Fetch lectures for dropdown
            async function fetchLectures() {
                try {
                    const response = await fetch('/admin/api/lectures', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const lectures = await response.json();
                    lectures.forEach(lecture => {
                        const option = document.createElement('option');
                        option.value = lecture.id;
                        option.textContent = `${lecture.subject ? lecture.subject.name : 'N/A'} - ${lecture.title} - ${lecture.hall.hall_name}`;
                        option.dataset.subject = lecture.subject ? lecture.subject.name : 'N/A';
                        option.dataset.professor = lecture.user.name;
                        option.dataset.hall = lecture.hall.hall_name;
                        lectureSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error fetching lectures:', error);
                }
            }

            // Update lecture info display
            lectureSelect.addEventListener('change', () => {
                const selectedOption = lectureSelect.options[lectureSelect.selectedIndex];
                if (selectedOption.value) {
                    infoTitle.textContent = selectedOption.textContent;
                    infoProfessor.textContent = selectedOption.dataset.professor;
                    infoHall.textContent = selectedOption.dataset.hall;
                    lectureInfo.classList.remove('hidden');
                    generateBtn.disabled = false;
                    qrCodeContainer.innerHTML = 'Select a lecture and generate QR code';
                } else {
                    lectureInfo.classList.add('hidden');
                    generateBtn.disabled = true;
                    qrCodeContainer.innerHTML = 'Select a lecture and generate QR code';
                }
            });

            // Generate QR code
            generateBtn.addEventListener('click', async () => {
                if (!lectureSelect.value) return;
                generateBtn.disabled = true;
                loadingSpinner.classList.remove('hidden');
                qrCodeContainer.innerHTML = '';

                try {
                    const response = await fetch('/admin/api/generate-qr', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ lecture_id: lectureSelect.value })
                    });
                    const data = await response.json();
                    if (data.success) {
                        qrCodeContainer.innerHTML = data.qr_code_svg;
                    } else {
                        qrCodeContainer.textContent = 'Failed to generate QR code';
                    }
                } catch (error) {
                    console.error('Error generating QR code:', error);
                    qrCodeContainer.textContent = 'Error generating QR code';
                } finally {
                    generateBtn.disabled = false;
                    loadingSpinner.classList.add('hidden');
                }
            });

            await fetchLectures();
        });
    </script>
</x-admin-layout>
