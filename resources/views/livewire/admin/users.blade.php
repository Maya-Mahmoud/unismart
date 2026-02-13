<div class="p-6">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">إدارة المستخدمين</h2>
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">{{ $editingUserId ? 'تعديل مستخدم' : 'إضافة مستخدم جديد' }}</h3>
        <form wire:submit.prevent="{{ $editingUserId ? 'update' : 'store' }}">
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="name" class="block text-gray-700 font-bold mb-2">الاسم</label>
                    <input type="text" id="name" wire:model="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-bold mb-2">البريد الإلكتروني</label>
                    <input type="email" id="email" wire:model="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="role" class="block text-gray-700 font-bold mb-2">الصلاحية</label>
                    <select id="role" wire:model="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="">اختر صلاحية</option>
                        <option value="admin">مشرف</option>
                        <option value="professor">أستاذ</option>
                        <option value="student">طالب</option>
                    </select>
                    @error('role') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @if (!$editingUserId)
                <div>
                    <label for="password" class="block text-gray-700 font-bold mb-2">كلمة المرور</label>
                    <input type="password" id="password" wire:model="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif
            </div>
            <div class="mt-6 flex gap-4">
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    {{ $editingUserId ? 'تحديث' : 'إضافة مستخدم' }}
                </button>
                @if ($editingUserId)
                    <button type="button" wire:click="cancelEdit" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        إلغاء
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">المستخدمون الحاليون</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الصلاحية</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->role }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button wire:click="edit({{ $user->id }})" class="text-indigo-600 hover:text-indigo-900 mr-2">تعديل</button>
                            <button wire:click="delete({{ $user->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">حذف</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
