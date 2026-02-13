<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('لوحة تحكم الأستاذ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <h1 class="text-2xl font-bold mb-4">أهلاً بك في لوحة تحكم الأستاذ.</h1>
                    <p>هنا يمكنك إدارة المواد الدراسية ودرجات الطلاب.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>