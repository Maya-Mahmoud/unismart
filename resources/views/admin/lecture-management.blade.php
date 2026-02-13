<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
   
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="section-header">
            <h1 class="section-title">Lectures</h1>
            <p class="section-subtitle">Manage lecture schedules and assignments</p>
        </div>

        <!-- Add Lecture Button -->
        <div class="flex justify-end mb-6">
            <button id="addLectureBtn" class="btn btn-green">
                + Add Lecture
            </button>
        </div>
       
        <!-- Lectures Grid -->
        <div id="lecturesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Lectures will be loaded here dynamically -->
        </div>
    </div>

    <!-- Add Lecture Modal -->
    <div id="addLectureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Lecture</h3>
                    <button id="closeAddModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addLectureForm">
                    @csrf
                    <div class="mb-4 ">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lecture Title</label>
                        <input type="text" id="lectureTitle" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select id="lectureDepartment" name="department" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->name }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select id="lectureYear" name="year" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a year</option>
                            <option value="first">First</option>
                            <option value="second">Second</option>
                            <option value="third">Third</option>
                            <option value="fourth">Fourth</option>
                            <option value="fifth">Fifth</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select id="lectureSemester" name="semester" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a semester</option>
                            <option value="first">First</option>
                            <option value="second">Second</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="lectureSubject" name="subject_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" data-department="{{ $subject->department }}" data-year="{{ $subject->year }}" data-semester="{{ $subject->semester }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Professor</label>
                        <select id="lectureProfessor" name="professor_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a professor</option>
                            @foreach($professors as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                        <input type="datetime-local" id="lectureStartTime" name="start_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                        <input type="datetime-local" id="lectureEndTime" name="end_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hall</label>
                        <select id="lectureHall" name="hall_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a hall</option>
                            @foreach($halls as $hall)
                                <option value="{{ $hall->id }}">{{ $hall->hall_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelAddBtn"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                                 class="btn2">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('lectureDepartment');
            const yearSelect = document.getElementById('lectureYear');
            const semesterSelect = document.getElementById('lectureSemester');
            const subjectSelect = document.getElementById('lectureSubject');

            function filterSubjects() {
                const selectedDepartment = departmentSelect.value;
                const selectedYear = yearSelect.value;
                const selectedSemester = semesterSelect.value;

                for (const option of subjectSelect.options) {
                    const matchesDepartment = !selectedDepartment || option.dataset.department === selectedDepartment;
                    const matchesYear = !selectedYear || option.dataset.year === selectedYear;
                    const matchesSemester = !selectedSemester || option.dataset.semester === selectedSemester;

                    option.style.display = (matchesDepartment && matchesYear && matchesSemester) ? '' : 'none';
                }

                // Reset subject selection if current selection is hidden
                if (subjectSelect.selectedOptions.length > 0) {
                    const selectedOption = subjectSelect.selectedOptions[0];
                    if (selectedOption.style.display === 'none') {
                        subjectSelect.value = '';
                    }
                }
            }

            departmentSelect.addEventListener('change', filterSubjects);
            yearSelect.addEventListener('change', filterSubjects);
            semesterSelect.addEventListener('change', filterSubjects);
        });
    </script>

    <!-- Edit Lecture Modal -->
    <div id="editLectureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Lecture</h3>
                    <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editLectureForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editLectureId" name="id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lecture Title</label>
                        <input type="text" id="editLectureTitle" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="editLectureSubject" name="subject" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hall</label>
                        <select id="editLectureHall" name="hall_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">Select a hall</option>
                            @foreach($halls as $hall)
                                <option value="{{ $hall->id }}">{{ $hall->hall_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                        <input type="datetime-local" id="editLectureStartTime" name="start_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                        <input type="datetime-local" id="editLectureEndTime" name="end_time" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelEditBtn"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteLectureModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Confirm Delete</h3>
                    <button id="closeDeleteModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg> 
                    </button>
                </div>

                <p class="text-gray-700 mb-4">Are you sure you want to delete this lecture?</p>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="button" id="confirmDeleteBtn"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        let apiBaseUrl;

        // تحديد عنوان URL الخاص بالـ API بناءً على دور المستخدم
        const userRole = "{{ Auth::user()->role }}";
        if (userRole === 'professor') {
            apiBaseUrl = '/professor/api';
        } else if (userRole === 'admin') {
            apiBaseUrl = '/admin/api';
        } else {
            apiBaseUrl = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addLectureBtn = document.getElementById('addLectureBtn');
            const addLectureModal = document.getElementById('addLectureModal');
            const closeAddModal = document.getElementById('closeAddModal');
            const cancelAddBtn = document.getElementById('cancelAddBtn');
            const addLectureForm = document.getElementById('addLectureForm');
            const lecturesGrid = document.getElementById('lecturesGrid');

            let lectureToDelete = null;

            // إظهار النافذة
            addLectureBtn.addEventListener('click', function() {
                addLectureModal.classList.remove('hidden');
            });

            // إخفاء النافذة
            function hideAddModal() {
                addLectureModal.classList.add('hidden');
                addLectureForm.reset();
            }

            closeAddModal.addEventListener('click', hideAddModal);
            cancelAddBtn.addEventListener('click', hideAddModal);

            // إغلاق النافذة عند الضغط خارجها
            addLectureModal.addEventListener('click', function(e) {
                if (e.target === addLectureModal) {
                    hideAddModal();
                }
            });

            // Function to load available halls based on selected time
            async function loadAvailableHalls() {
                const startTime = document.getElementById('lectureStartTime').value;
                const endTime = document.getElementById('lectureEndTime').value;
                const hallSelect = document.getElementById('lectureHall');

                // Always try to load halls, even if no times are selected
                try {
                    let url = `${apiBaseUrl}/available-halls`;
                    if (startTime && endTime) {
                        url += `?start_time=${encodeURIComponent(startTime)}&end_time=${encodeURIComponent(endTime)}`;
                    }

                    const response = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const halls = await response.json();
                    hallSelect.innerHTML = '<option value="">Select a hall</option>';

                    halls.forEach(hall => {
                        const option = document.createElement('option');
                        option.value = hall.id;
                        option.textContent = hall.hall_name;
                        hallSelect.appendChild(option);
                    });
                } catch (error) {
                    console.error('Error loading available halls:', error);
                    // Fallback: show error message
                    hallSelect.innerHTML = '<option value="">Error loading halls</option>';
                }
            }

            // Add event listeners for time inputs
            document.getElementById('lectureStartTime').addEventListener('change', loadAvailableHalls);
            document.getElementById('lectureEndTime').addEventListener('change', loadAvailableHalls);

            // Load halls initially when modal opens
            addLectureBtn.addEventListener('click', function() {
                loadAvailableHalls();
            });

            // معالجة إرسال النموذج
            addLectureForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                try {
                    const response = await fetch(`${apiBaseUrl}/lectures`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Lecture created successfully!');
                        hideAddModal();
                        loadLectures(); // إعادة تحميل المحاضرات
                    } else {
                        alert(result.message || 'Error creating lecture');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error creating lecture');
                }
            });

            // تحميل المحاضرات عند تحميل الصفحة
            loadLectures();

            async function loadLectures() {
                try {
                    const response = await fetch(`${apiBaseUrl}/lectures`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    const lectures = await response.json();

                    updateLecturesGrid(lectures);
                } catch (error) {
                    console.error('Error loading lectures:', error);
                }
            }

            function updateLecturesGrid(lectures) {
                if (lectures.length === 0) {
                    lecturesGrid.innerHTML = '<p class="text-center text-gray-500">No lectures found. Add your first lecture!</p>';
                    return;
                }

                lecturesGrid.innerHTML = lectures.map(lecture => {
                    const startDate = new Date(lecture.start_time).toLocaleDateString();
                    const startTime = new Date(lecture.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    const endTime = new Date(lecture.end_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

                    const subjectName = lecture.subject && lecture.subject.name ? lecture.subject.name : 'N/A';
                    const userName = lecture.user && lecture.user.name ? lecture.user.name : 'N/A';
                    const hallName = lecture.hall && lecture.hall.hall_name ? lecture.hall.hall_name : 'N/A';

                    return `
                        <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm hall-card">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-900">${subjectName} / ${lecture.title}</h3>
                                <div class="flex items-center space-x-2">
                                  
                                    <button onclick="deleteLecture(${lecture.id})" class="text-red-600 hover:text-red-900 transition-colors duration-200 transform hover:scale-110">
                                        <svg class="w-5 h-5 !stroke-red-600" fill="none" viewBox="0 0 24 24" style="stroke: #ef4444 !important;">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>  
                                    </button>
                                </div>
                            </div>

                            <div class="space-y-3 mb-4">
                                <div class="lecturer-badge">
                                     <span>by ${userName}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="font-medium">${startDate}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    ${startTime} - ${endTime}
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Hall: <span class="font-medium ml-1">${hallName}</span>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <a href="/admin/api/lectures/${lecture.id}/attendance" class="flex-1 text-white bg-gradient-to-r from-purple-700 to-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:opacity-90 transition-opacity flex items-center" style="background: linear-gradient(90deg, #7C3AED, #2563EB);">
                                    View Attendance</a>
                                <button onclick="editLecture(${lecture.id})" class=" flex-1 bg-gray-200  px-3 py-1 rounded-md bg-purple-100 text-purple-900 hover:bg-purple-200">
                                    Edit
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            window.deleteLecture = async function deleteLecture(lectureId) {
                lectureToDelete = lectureId;
                const deleteModal = document.getElementById('deleteLectureModal');
                deleteModal.classList.remove('hidden');
            }

            async function confirmDelete() {
                if (!lectureToDelete) return;

                try {
                    const response = await fetch(`${apiBaseUrl}/lectures/${lectureToDelete}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        alert('Lecture deleted successfully!');
                        hideDeleteModal();
                        loadLectures(); // إعادة تحميل المحاضرات
                    } else {
                        const result = await response.json();
                        alert(result.message || 'Error deleting lecture');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error deleting lecture');
                }
            }

            window.viewAttendance = function(lectureId) {
                alert('Attendance view coming soon!');
                // TODO: Implement attendance view
            };

            window.editLecture = async function(lectureId) {
                try {
                    const response = await fetch(`${apiBaseUrl}/lectures/${lectureId}`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }

                    const lecture = await response.json();

                    if (!lecture || !lecture.id) {
                        throw new Error('Invalid or empty lecture data');
                    }

                    // ملء النموذج
                    document.getElementById('editLectureId').value = lecture.id || '';
                    document.getElementById('editLectureTitle').value = lecture.title || '';
                    document.getElementById('editLectureSubject').value = lecture.subject || '';
                    document.getElementById('editLectureHall').value = lecture.hall_id || '';
                    document.getElementById('editLectureStartTime').value = lecture.start_time ? new Date(lecture.start_time).toISOString().slice(0, 16) : '';
                    document.getElementById('editLectureEndTime').value = lecture.end_time ? new Date(lecture.end_time).toISOString().slice(0, 16) : '';

                    // إظهار النافذة
                    const editLectureModal = document.getElementById('editLectureModal');
                    editLectureModal.classList.remove('hidden');
                } catch (error) {
                    console.error('Error in editLecture:', error);
                    alert('Failed to load lecture data: ' + error.message);
                }
            };

            // معالجة النافذة الخاصة بالتعديل
            const editLectureModal = document.getElementById('editLectureModal');
            const closeEditModal = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const editLectureForm = document.getElementById('editLectureForm');

            function hideEditModal() {
                editLectureModal.classList.add('hidden');
                editLectureForm.reset();
            }

            closeEditModal.addEventListener('click', hideEditModal);
            cancelEditBtn.addEventListener('click', hideEditModal);

            editLectureModal.addEventListener('click', function(e) {
                if (e.target === editLectureModal) {
                    hideEditModal();
                }
            });

            editLectureForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                const lectureId = data.id;

                try {
                    const response = await fetch(`${apiBaseUrl}/lectures/${lectureId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Lecture updated successfully!');
                        hideEditModal();
                        loadLectures(); // إعادة تحميل المحاضرات
                    } else {
                        alert(result.message || 'Error updating lecture');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error updating lecture');
                }
            });

            // Delete Modal Event Listeners
            const deleteModal = document.getElementById('deleteLectureModal');
            const closeDeleteModal = document.getElementById('closeDeleteModal');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            function hideDeleteModal() {
                deleteModal.classList.add('hidden');
                lectureToDelete = null;
            }

            closeDeleteModal.addEventListener('click', hideDeleteModal);
            cancelDeleteBtn.addEventListener('click', hideDeleteModal);
            confirmDeleteBtn.addEventListener('click', confirmDelete);

            deleteModal.addEventListener('click', function(e) {
                if (e.target === deleteModal) {
                    hideDeleteModal();
                }
            });
        });
    </script>
</x-admin-layout>