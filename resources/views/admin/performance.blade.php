<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Performance Analysis</h1>
            <p class="text-gray-600">Monitor attendance metrics</p>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow-sm rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Select year, semester, department, and subject to view attendance percentage:</h3>
                <form id="filterForm" class="flex flex-wrap gap-4 items-end">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Year</label>
                        <select id="year" name="year" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Years</option>
                            <option value="first">First</option>
                            <option value="second">Second</option>
                            <option value="third">Third</option>
                            <option value="fourth">Fourth</option>
                            <option value="fifth">Fifth</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                        <select id="semester" name="semester" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Semesters</option>
                            <option value="first">First</option>
                            <option value="second">Second</option>
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <select id="department_id" name="department_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                        <select id="subject_id" name="subject_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Subjects</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        {{-- <button type="button" id="filterBtn" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-purple-700">search</button>
                        <button type="button" id="clearBtn" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-600">Clear</button> --}}
                       <button type="button" id="exportCsvBtn" class="flex items-center bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-700">
    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    Export CSV
</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Total Students</h3>
                        <p id="totalStudents" class="text-2xl font-bold text-blue-600">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Average Attendance</h3>
                        <p id="averageAttendance" class="text-2xl font-bold text-green-600">0%</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Average Absence</h3>
                        <p id="averageAbsence" class="text-2xl font-bold text-red-600">0%</p>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Number of lectures</h3>
                        <p id="totalLectures" class="text-2xl font-bold text-purple-600">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Data -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subject Performance</h3>
                <div id="performanceData">
                    <p class="text-gray-600">Select filters to view performance data.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtn = document.getElementById('filterBtn');
            const clearBtn = document.getElementById('clearBtn');
            const exportCsvBtn = document.getElementById('exportCsvBtn');
            const performanceData = document.getElementById('performanceData');
            const yearSelect = document.getElementById('year');
            const semesterSelect = document.getElementById('semester');
            const departmentSelect = document.getElementById('department_id');
            const subjectSelect = document.getElementById('subject_id');
            const totalStudents = document.getElementById('totalStudents');
            const averageAttendance = document.getElementById('averageAttendance');
            const averageAbsence = document.getElementById('averageAbsence');
            const totalLectures = document.getElementById('totalLectures');

            // Load subjects and stats when filters change
            [yearSelect, semesterSelect, departmentSelect].forEach(select => {
                select.addEventListener('change', function() {
                    loadSubjects();
                    loadStats();
                });
            });

            subjectSelect.addEventListener('change', function() {
                loadSubjectStats();
                exportCsvBtn.disabled = !subjectSelect.value;
            });

            if (filterBtn) {
                filterBtn.addEventListener('click', function() {
                    loadPerformanceData();
                });
            }

            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    yearSelect.value = '';
                    semesterSelect.value = '';
                    departmentSelect.value = '';
                    subjectSelect.innerHTML = '<option value="">All Subjects</option>';
                    performanceData.innerHTML = '<p class="text-gray-600">Select filters to view performance data.</p>';
                    // Reset stats to 0
                    totalStudents.textContent = '0';
                    averageAttendance.textContent = '0%';
                    averageAbsence.textContent = '0%';
                    totalLectures.textContent = '0';
                    exportCsvBtn.disabled = true;
                });
            }

            exportCsvBtn.addEventListener('click', function() {
                const subjectId = subjectSelect.value;
                if (!subjectId) {
                    alert('Please select a subject first.');
                    return;
                }
                // Create a link to download the CSV
                const link = document.createElement('a');
                link.href = `/admin/performance/export-csv?subject_id=${subjectId}`;
                link.download = 'subject_attendance.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            function loadSubjects() {
                const year = yearSelect.value;
                const semester = semesterSelect.value;
                const departmentId = departmentSelect.value;

                const params = new URLSearchParams();
                if (year) params.append('year', year);
                if (semester) params.append('semester', semester);
                if (departmentId) params.append('department_id', departmentId);

                fetch(`/admin/api/subjects-performance?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    updateSubjectSelect(data);
                })
                .catch(error => {
                    console.error('Error loading subjects:', error);
                });
            }

            function updateSubjectSelect(subjects) {
                subjectSelect.innerHTML = '<option value="">All Subjects</option>';
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
            }

            function loadStats() {
                const year = yearSelect.value;
                const departmentId = departmentSelect.value;

                const params = new URLSearchParams();
                if (year) params.append('year', year);
                if (departmentId) params.append('department_id', departmentId);

                fetch(`/admin/api/stats?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    totalStudents.textContent = data.total_students;
                    averageAttendance.textContent = '0%';
                    averageAbsence.textContent = '0%';
                    totalLectures.textContent = '0';
                })
                .catch(error => {
                    console.error('Error loading stats:', error);
                });
            }

            function loadSubjectStats() {
                const subjectId = subjectSelect.value;

                if (!subjectId) {
                    averageAttendance.textContent = '0%';
                    averageAbsence.textContent = '0%';
                    totalLectures.textContent = '0';
                    return;
                }

                const params = new URLSearchParams();
                params.append('subject_id', subjectId);

                fetch(`/admin/api/subject-stats?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    averageAttendance.textContent = data.average_attendance + '%';
                    averageAbsence.textContent = data.average_absence + '%';
                    totalLectures.textContent = data.total_lectures;
                })
                .catch(error => {
                    console.error('Error loading subject stats:', error);
                });
            }

            function loadPerformanceData() {
                const year = yearSelect.value;
                const semester = semesterSelect.value;
                const departmentId = departmentSelect.value;
                const subjectId = subjectSelect.value;

                const params = new URLSearchParams();
                if (year) params.append('year', year);
                if (semester) params.append('semester', semester);
                if (departmentId) params.append('department_id', departmentId);
                if (subjectId) params.append('subject_id', subjectId);

                fetch(`/admin/performance?${params.toString()}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    displayPerformanceData(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    performanceData.innerHTML = '<p class="text-red-600">Error loading data.</p>';
                });
            }

            function displayPerformanceData(subjects) {
                if (subjects.length === 0) {
                    performanceData.innerHTML = '<p class="text-gray-600">No subjects found matching the criteria.</p>';
                    return;
                }

                let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
                subjects.forEach(subject => {
                    const attendanceRate = subject.attendance_rate;
                    const rateColor = attendanceRate >= 80 ? 'text-green-600' : attendanceRate >= 60 ? 'text-yellow-600' : 'text-red-600';

                    html += `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-md font-medium text-gray-900 mb-2">${subject.name}</h4>
                            <p class="text-sm text-gray-600">Year: ${subject.year.charAt(0).toUpperCase() + subject.year.slice(1)} | Semester: ${subject.semester.charAt(0).toUpperCase() + subject.semester.slice(1)}</p>
                            <p class="text-sm text-gray-600">Department: ${subject.department}</p>
                            <p class="text-sm text-gray-600">Total Lectures: ${subject.total_lectures}</p>
                            <p class="text-sm text-gray-600">Total Presence: ${subject.total_presence}</p>
                            <p class="text-sm text-gray-600">Total Absence: ${subject.total_absence}</p>
                            <p class="text-sm ${rateColor}">Attendance Rate: ${attendanceRate}%</p>
                        </div>
                    `;
                });
                html += '</div>';
                performanceData.innerHTML = html;
            }
        });
    </script>
</x-admin-layout>
