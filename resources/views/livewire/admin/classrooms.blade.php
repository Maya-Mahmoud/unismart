<div class="p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">إدارة القاعات</h2>
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $editingClassroomId ? 'تحديث القاعة' : 'إضافة قاعة جديدة' }}</h3>
        <form wire:submit.prevent="{{ $editingClassroomId ? 'update' : 'store' }}">
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 font-bold mb-2">اسم القاعة</label>
                    <input type="text" id="name" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="capacity" class="block text-gray-700 font-bold mb-2">السعة</label>
                    <input type="number" id="capacity" wire:model="capacity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('capacity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="location" class="block text-gray-700 font-bold mb-2">الموقع</label>
                    <input type="text" id="location" wire:model="location" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ $editingClassroomId ? 'تحديث' : 'إضافة قاعة' }}
                </button>
                @if ($editingClassroomId)
                    <button type="button" wire:click="cancelEdit" class="ml-4 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        إلغاء
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">القاعات الحالية</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">اسم القاعة</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">السعة</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الموقع</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($classrooms as $classroom)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $classroom->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $classroom->capacity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $classroom->location ?? 'غير محدد' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="edit({{ $classroom->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-sm mr-2">تعديل</button>
                            <button wire:click="delete({{ $classroom->id }})" onclick="return confirm('هل أنت متأكد من حذف هذه القاعة؟')" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-sm">حذف</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
