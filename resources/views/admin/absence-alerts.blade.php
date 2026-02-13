<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Absence Alerts</h1>
                <p class="text-gray-600">Students with absence rate above 55% requiring attention</p>
            </div>
            <button id="sendAllAlertsBtn" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 hover:shadow-lg flex items-center transition-all duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Send Alerts to All
            </button>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Students with Alerts</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $studentsWithAlerts->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Absence Records</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $alerts->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Absences</dt>
                                <dd class="text-lg font-medium text-gray-900">{{ $alerts->sum('absence_count') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students with Alerts -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Students Requiring Attention</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Students with absence rate above 55% in any subject</p>
            </div>

            @if($studentsWithAlerts->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No alerts</h3>
                    <p class="mt-1 text-sm text-gray-500">All students are within acceptable absence limits.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($studentsWithAlerts as $student)
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student['student_name'] }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $student['department'] }} - Year {{ $student['year'] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="text-right mr-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $student['overall_absence_rate'] }}% absence rate</div>
                                        <div class="text-sm text-gray-500">{{ $student['total_absences'] }} absences / {{ $student['total_lectures'] }} lectures</div>
                                    </div>
                                    <button class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-medium hover:bg-blue-100 transition-colors"
                                            onclick="sendAlert({{ $student['student_id'] }}, '{{ $student['student_name'] }}')">
                                        Send Alert
                                    </button>
                                    <button class="bg-red-50 text-red-700 px-3 py-1 rounded-full text-xs font-medium hover:bg-red-100 transition-colors"
                                            onclick="showStudentDetails({{ $student['student_id'] }})">
                                        View Details
                                    </button>
                                </div>
                            </div>

                            <!-- Subject details (collapsed by default) -->
                            <div id="student-details-{{ $student['student_id'] }}" class="mt-4 hidden">
                                <div class="border-t border-gray-200 pt-4">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Absence Details by Subject:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($student['subjects'] as $subject)
                                            <div class="bg-gray-50 rounded-lg p-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $subject['subject_name'] }}</div>
                                                <div class="text-sm text-red-600">{{ $subject['absence_percentage'] }}% ({{ $subject['absence_count'] }}/{{ $subject['total_lectures'] }})</div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <script>
        function showStudentDetails(studentId) {
            const detailsDiv = document.getElementById(`student-details-${studentId}`);
            if (detailsDiv.classList.contains('hidden')) {
                detailsDiv.classList.remove('hidden');
            } else {
                detailsDiv.classList.add('hidden');
            }
        }

        function sendAlert(studentId, studentName) {
            if (confirm(`Are you sure you want to send an absence alert to ${studentName}?`)) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch(`/admin/absence/alerts/send/${studentId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Alert sent successfully to ${studentName}`);
                    } else {
                        alert('Failed to send alert');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while sending the alert');
                });
            }
        }

        document.getElementById('sendAllAlertsBtn').addEventListener('click', function() {
            if (confirm('Are you sure you want to send absence alerts to all students with high absences?')) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch('/admin/absence/alerts/send-all', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert('Failed to send alerts');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while sending alerts');
                });
            }
        });
    </script>
</x-admin-layout>
