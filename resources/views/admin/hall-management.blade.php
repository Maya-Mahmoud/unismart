<x-admin-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="section-header">
            <h1 class="section-title">Hall Management</h1>
            <p class="section-subtitle">Monitor hall capacity, bookings, and availability</p>
        </div>
        

        <!-- Sub Navigation -->
        <div class="hall-card rounded-xl mb-8">
            
                <nav class="flex space-x-8">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">
                        <svg class="w-5 h-5 mr-3"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Overview
                    </a>
                    <a href="{{ route('admin.users') }}" class="nav-link">
                        <svg class="w-5 h-5 mr-3"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Users
                    </a>
                   <a href="#"  class="nav-link active">
                        <svg class="w-5 h-5 mr-3"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Halls
                    </a>
                    <a href="{{ route('admin.subjects') }}" class="nav-link">
                        <svg class="w-5 h-5 mr-3"  fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Subject
                    </a>
                </nav>
            
        </div>

        <!-- Header with Add Hall Button -->
        <div class="flex justify-between items-center mb-6">
             <div class="section-header">
                <h2 class="section-title">Add college halls</h2>
                <p class="section-subtitle">Manage your college halls and facilities</p>
            </div>
          
            <button id="addHallBtn"  class="btn btn-green flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Hall
            </button>
        </div>

        <!-- Halls Grid -->
        <div id="hallsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Halls will be loaded here dynamically -->
        </div>
    </div>

    <!-- Add Hall Modal -->
    <div id="addHallModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Add New Hall</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="addHallForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hall Name</label>
                        <input type="text" id="hallName" name="hall_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                        <input type="number" id="hallCapacity" name="capacity" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Building</label>
                        <input type="text" id="hallBuilding" name="building" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Floor</label>
                        <input type="number" id="hallFloor" name="floor" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Equipment (comma-separated)</label>
                        <input type="text" id="hallEquipment" name="equipment"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Projector, Sound System, Microphone">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelBtn"
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

    <!-- Edit Hall Modal -->
    <div id="editHallModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Edit Hall</h3>
                    <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="editHallForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="editHallId" name="id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hall Name</label>
                        <input type="text" id="editHallName" name="hall_name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Capacity</label>
                        <input type="number" id="editHallCapacity" name="capacity" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Building</label>
                        <input type="text" id="editHallBuilding" name="building" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Floor</label>
                        <input type="number" id="editHallFloor" name="floor" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>



                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Equipment (comma-separated)</label>
                        <input type="text" id="editHallEquipment" name="equipment"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                               placeholder="Projector, Sound System, Microphone">
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

    <!-- JavaScript -->
    <script>
        // --- Make loadHalls a global function so deleteHall can call it ---
        async function loadHalls() {
            try {
                const response = await fetch('/admin/api/halls');
                const halls = await response.json();

                updateHallsGrid(halls);
            } catch (error) {
                console.error('Error loading halls:', error);
            }
        }

        // updateHallsGrid also needs to be globally accessible or defined inside the DOMContentLoaded
        // Since loadHalls is calling it, we'll define it globally too.
        function updateHallsGrid(halls) {
            const hallsGrid = document.getElementById('hallsGrid');
            if (halls.length === 0) {
                hallsGrid.innerHTML = '<p class="text-center text-gray-500">No halls found. Add your first hall!</p>';
                return;
            }

            hallsGrid.innerHTML = halls.map(hall => {
                const statusColors = {
                    'available': 'green',
                    'booked': 'red',
                    'maintenance': 'yellow'
                };

                const equipmentList = hall.equipment ? hall.equipment.split(',').map(item => item.trim()) : [];

                return `
                    <div class="hall-card">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">${hall.hall_name}</h3>
                            <div class="flex items-center space-x-2">
                                <button onclick="window.editHall(${hall.id})" class="text-purple-600 hover:text-purple-800 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <button onclick="window.deleteHall(${hall.id})" class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-3 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                ${hall.building}, Floor ${hall.floor}
                            </div>

                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Capacity: <span class="font-medium ml-1">${hall.capacity}</span>
                            </div>

                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Status: <span class="font-medium ml-1 text-${statusColors[hall.status] || 'gray'}-600 capitalize">${hall.status}</span>
                            </div>
                        </div>

                        ${equipmentList.length > 0 ? `
                            <div class="mb-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Equipment:</p>
                                <div class="flex flex-wrap gap-2">
                                    ${equipmentList.map(item => `<span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded">${item}</span>`).join('')}
                                </div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }).join('');
        }

        // --- Define deleteHall globally using 'window' ---
        window.deleteHall = async function(hallId) {
            if (confirm('Are you sure you want to delete this hall? This action cannot be undone.')) {
                try {
                    const response = await fetch(`/admin/api/halls/${hallId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    if (response.ok) {
                        alert('Hall deleted successfully!');
                        loadHalls(); // Reload halls
                    } else {
                        const result = await response.json();
                        alert(result.message || 'Error deleting hall');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error deleting hall');
                }
            }
        };

        window.editHall = async function(hallId) {
            try {
                const response = await fetch(`/admin/api/halls/${hallId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const hall = await response.json();

                if (!hall || !hall.id) {
                    throw new Error('Invalid or empty hall data');
                }

                // Populate form with fallback values
                document.getElementById('editHallId').value = hall.id || '';
                document.getElementById('editHallName').value = hall.hall_name || '';
                document.getElementById('editHallCapacity').value = hall.capacity || '';
                document.getElementById('editHallBuilding').value = hall.building || '';
                document.getElementById('editHallFloor').value = hall.floor || '';
                document.getElementById('editHallEquipment').value = hall.equipment || '';

                // Show modal
                const editHallModal = document.getElementById('editHallModal');
                if (!editHallModal) {
                    throw new Error('Edit modal element not found');
                }
                editHallModal.classList.remove('hidden');
            } catch (error) {
                console.error('Error in editHall:', error);
                alert('Failed to load hall data: ' + error.message);
            }
        };

        // --- DOMContentLoaded for initial setup and modal handlers ---
        document.addEventListener('DOMContentLoaded', function() {
            const addHallBtn = document.getElementById('addHallBtn');
            const addHallModal = document.getElementById('addHallModal');
            const closeModal = document.getElementById('closeModal');
            const cancelBtn = document.getElementById('cancelBtn');
            const addHallForm = document.getElementById('addHallForm');

            // Show modal
            addHallBtn.addEventListener('click', function() {
                addHallModal.classList.remove('hidden');
            });

            // Hide modal
            function hideModal() {
                addHallModal.classList.add('hidden');
                addHallForm.reset();
            }

            closeModal.addEventListener('click', hideModal);
            cancelBtn.addEventListener('click', hideModal);

            // Close modal when clicking outside
            addHallModal.addEventListener('click', function(e) {
                if (e.target === addHallModal) {
                    hideModal();
                }
            });

            // Handle Add Hall form submission
            addHallForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());

                try {
                    const response = await fetch('/admin/api/halls', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Hall created successfully!');
                        hideModal();
                        loadHalls(); // Reload halls
                    } else {
                        alert(result.message || 'Error creating hall');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error creating hall');
                }
            });

            // Load halls on page load
            loadHalls();

            // Edit modal handlers
            const editHallModal = document.getElementById('editHallModal');
            const closeEditModal = document.getElementById('closeEditModal');
            const cancelEditBtn = document.getElementById('cancelEditBtn');
            const editHallForm = document.getElementById('editHallForm');

            function hideEditModal() {
                editHallModal.classList.add('hidden');
                editHallForm.reset();
            }

            closeEditModal.addEventListener('click', hideEditModal);
            cancelEditBtn.addEventListener('click', hideEditModal);

            editHallModal.addEventListener('click', function(e) {
                if (e.target === editHallModal) {
                    hideEditModal();
                }
            });

            editHallForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                const hallId = data.id;

                try {
                    const response = await fetch(`/admin/api/halls/${hallId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (response.ok) {
                        alert('Hall updated successfully!');
                        hideEditModal();
                        loadHalls(); // Reload halls
                    } else {
                        alert(result.message || 'Error updating hall');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error updating hall');
                }
            });
        });
    </script>
</x-admin-layout>