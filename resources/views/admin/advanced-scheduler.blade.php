<x-admin-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-16">
        <!-- Page Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Advanced Scheduler</h1>
                <p class="text-gray-600 mt-1">Schedule lectures with conflict detection and recurring options</p>
            </div>
            <button id="scheduleLectureBtn" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded shadow">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Schedule Lecture
            </button>
        </div>

        <div class="bg-white rounded-lg shadow p-6 my-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Schedule Overview</h2>
                <input type="date" id="scheduleDate" class="border border-gray-300 rounded px-3 py-1" value="{{ date('Y-m-d') }}" />
            </div>
            <div id="scheduleOverview" class="flex flex-col py-4 text-gray-700 mx-auto max-w-3xl">
                <!-- Lectures will be rendered here dynamically -->
            </div>
        </div>

        <!-- Schedule Lecture Modal -->
        <div id="scheduleLectureModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
            <div class="bg-white rounded-lg p-6 w-[600px]">
                <h3 class="text-xl font-semibold mb-4" style="color: #6B46C1;">Schedule New Lecture</h3>
                <form id="scheduleLectureForm" class="space-y-4">
                    <div class="flex space-x-4">
                        <div class="w-1/3">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">Department</label>
                            <select id="department_id" name="department_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-40 overflow-y-auto">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-1/3">
                            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                            <select id="year" name="year" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-40 overflow-y-auto">
                                <option value="">Select Year</option>
                                <option value="first">First</option>
                                <option value="second">Second</option>
                                <option value="third">Third</option>
                                <option value="fourth">Fourth</option>
                                <option value="fifth">Fifth</option>
                            </select>
                        </div>
                        <div class="w-1/3">
                            <label for="semester" class="block text-sm font-medium text-gray-700">Semester</label>
                            <select id="semester" name="semester" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-40 overflow-y-auto">
                                <option value="">Select Semester</option>
                                <option value="first">First</option>
                                <option value="second">Second</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                        <select id="subject_id" name="subject_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-40 overflow-y-auto">
                            <option value="">Select Subject</option>
                        </select>
                    </div>
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Lecture Title</label>
                        <input type="text" id="title" name="title" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <div>
                        <label for="professor_id" class="block text-sm font-medium text-gray-700">Professor</label>
                        <select id="professor_id" name="professor_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-40 overflow-y-auto">
                            <option value="">Select Professor</option>
                            @foreach($professors as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="hall_id" class="block text-sm font-medium text-gray-700">Hall</label>
                        <select id="hall_id" name="hall_id" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 max-h-40 overflow-y-auto">
                            <option value="">Select a hall</option>
                        </select>
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                        <input type="datetime-local" id="start_time" name="start_time" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                        <input type="datetime-local" id="end_time" name="end_time" required class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <div>
                        <label for="max_students" class="block text-sm font-medium text-gray-700">Max Students</label>
                        <input type="number" id="max_students" name="max_students" value="50" min="1" class="mt-1 block w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <div>
                        <label class="inline-flex items-center mt-2">
                            <input type="checkbox" id="recurringLecture" name="recurringLecture" class="form-checkbox" />
                            <span class="ml-2 text-gray-700">Make this a recurring lecture</span>
                        </label>
                    </div>
                    <div id="recurringOptions" class="hidden flex space-x-6 mt-2">
                        <div class="flex flex-col">
                            <label for="repeat_pattern" class="block text-sm font-medium text-gray-700">Repeat Pattern</label>
                            <select id="repeat_pattern" name="repeat_pattern" class="mt-1 block w-40 border border-gray-300 rounded px-3 py-2">
                                <option value="daily">Daily</option>
                                <option value="weekly" selected>Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>
                        <div class="flex flex-col">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" id="end_date" name="end_date" class="mt-1 block w-40 border border-gray-300 rounded px-3 py-2" />
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" id="cancelBtn" class="px-4 py-2 rounded border border-gray-300 hover:bg-gray-100">Cancel</button>
                        <button type="submit" class="px-4 py-2 rounded bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700">Schedule Lecture</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            const scheduleLectureBtn = document.getElementById('scheduleLectureBtn');
            const scheduleLectureModal = document.getElementById('scheduleLectureModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const scheduleLectureForm = document.getElementById('scheduleLectureForm');

            scheduleLectureBtn.addEventListener('click', () => {
                scheduleLectureModal.classList.remove('hidden');
            });

            cancelBtn.addEventListener('click', () => {
                scheduleLectureModal.classList.add('hidden');
            });

            scheduleLectureForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const subjectSelect = document.getElementById('subject_id');
                if (!subjectSelect.value) {
                    alert('Please select a subject.');
                    return;
                }

                const formData = new FormData(scheduleLectureForm);
                const data = Object.fromEntries(formData.entries());

                data.recurringLecture = scheduleLectureForm.querySelector('#recurringLecture').checked;

                try {
                    const response = await fetch('/admin/api/lectures', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    if (response.ok) {
                        alert('Lecture scheduled successfully!');
                        scheduleLectureModal.classList.add('hidden');
                        window.location.href = "{{ route('admin.lectures') }}";
                    } else {
                        const errorData = await response.json();
                        alert(errorData.message || 'Failed to schedule lecture.');
                    }
                } catch (error) {
                    alert('Error scheduling lecture.');
                    console.error(error);
                }
            });

            // Fetch halls dynamically
            const hallDropdown = document.getElementById('hall_id');
            hallDropdown.addEventListener('focus', async () => {
                if (hallDropdown.options.length <= 1) {
                    try {
                        const response = await fetch('/admin/api/halls');
                        if (response.ok) {
                            const halls = await response.json();
                            halls.forEach(hall => {
                                const option = document.createElement('option');
                                option.value = hall.id;
                                option.textContent = hall.hall_name;
                                hallDropdown.appendChild(option);
                            });
                        }
                    } catch (error) {
                        console.error('Error fetching halls:', error);
                    }
                }
            });

            // Fetch and render lectures
            const scheduleDateInput = document.getElementById('scheduleDate');
            const scheduleOverview = document.getElementById('scheduleOverview');

            async function fetchLecturesByDate(date) {
                try {
                    const response = await fetch(`/admin/api/lectures-by-date?date=${date}`);
                    if (!response.ok) throw new Error('Failed to fetch lectures');
                    const lectures = await response.json();
                    renderLectures(lectures, date);
                } catch (error) {
                    console.error('Error fetching lectures:', error);
                    scheduleOverview.innerHTML = `<p class="text-red-500">Error loading lectures.</p>`;
                }
            }

            function renderLectures(lectures, date) {
                if (lectures.length === 0) {
                    const formattedDate = new Date(date).toLocaleDateString(undefined, { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    scheduleOverview.innerHTML = `
                        <div class="flex flex-col items-center justify-center py-20 text-gray-400">
                            <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <p>No lectures scheduled for ${formattedDate}</p>
                        </div>
                    `;
                    return;
                }
                scheduleOverview.innerHTML = '';
                lectures.forEach(lecture => {
                    const startTime = new Date(lecture.start_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    const endTime = new Date(lecture.end_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    const hallName = lecture.hall.hall_name || 'Unknown Hall';
                    const professorName = lecture.user ? `بروفيسور: ${lecture.user.name}` : 'Unknown Professor';
                    const lectureDiv = document.createElement('div');
                    lectureDiv.className = 'bg-gray-100 rounded p-4 mb-3 shadow w-full';
                    lectureDiv.innerHTML = `
                        <div class="flex justify-between items-center mb-1">
                            <h3 class="font-semibold text-lg">${lecture.title}</h3>
                            <div class="flex space-x-2">
                                <span class="text-xs bg-purple-200 text-purple-800 rounded px-2 py-0.5">${lecture.subject}</span>
                                ${lecture.repeat_pattern ? `<span class="text-xs bg-blue-200 text-blue-800 rounded px-2 py-0.5">${lecture.repeat_pattern}</span>` : ''}
                            </div>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span>🕒 ${startTime} - ${endTime}</span> &nbsp;|&nbsp;
                            <span>🏛️ ${hallName}</span> &nbsp;|&nbsp;
                            <span>👨‍🏫 ${professorName}</span>
                        </div>
                    `;
                    scheduleOverview.appendChild(lectureDiv);
                });
            }

            fetchLecturesByDate(scheduleDateInput.value);
            scheduleDateInput.addEventListener('change', (e) => fetchLecturesByDate(e.target.value));

            // Toggle recurring options
            const recurringCheckbox = document.getElementById('recurringLecture');
            const recurringOptions = document.getElementById('recurringOptions');
            recurringCheckbox.addEventListener('change', () => {
                recurringOptions.classList.toggle('hidden', !recurringCheckbox.checked);
            });

            // Cascading dropdowns for subjects
            const departmentDropdown = document.getElementById('department_id');
            const yearDropdown = document.getElementById('year');
            const semesterDropdown = document.getElementById('semester');
            const subjectDropdown = document.getElementById('subject_id');

            async function fetchSubjects() {
                const departmentId = departmentDropdown.value;
                const year = yearDropdown.value;
                const semester = semesterDropdown.value;

                if (!departmentId || !year || !semester) {
                    subjectDropdown.innerHTML = '<option value="">Select Subject</option>';
                    return;
                }

                try {
                    const response = await fetch(`/admin/api/subjects?department_id=${departmentId}&year=${year}&semester=${semester}`);
                    if (response.ok) {
                        const subjects = await response.json();
                        subjectDropdown.innerHTML = '<option value="">Select Subject</option>';
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectDropdown.appendChild(option);
                        });
                    }
                } catch (error) {
                    console.error('Error fetching subjects:', error);
                }
            }

            [departmentDropdown, yearDropdown, semesterDropdown].forEach(dropdown =>
                dropdown.addEventListener('change', fetchSubjects)
            );
        </script>
    </div>
</x-admin-layout>