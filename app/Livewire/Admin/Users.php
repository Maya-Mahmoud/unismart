<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class Users extends Component
{
    public $users;
    public $name;
    public $email;
    public $password;
    public $role;
    public $editingUserId = null;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'nullable|string|min:8',
            'role' => ['required', 'string', Rule::in(['admin', 'student', 'professor'])],
        ];
    }

    public function mount()
    {
        $this->users = User::all();
    }

    public function render()
    {
        return view('livewire.admin.users', [
            'users' => $this->users,
        ])->layout('layouts.app');
    }

    public function store()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => $this->role,
        ]);

        session()->flash('message', 'تم إضافة المستخدم بنجاح.');
        $this->reset(['name', 'email', 'password', 'role']);
        $this->mount();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->editingUserId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->editingUserId)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', 'string', Rule::in(['admin', 'student', 'professor'])],
        ]);

        $user = User::findOrFail($this->editingUserId);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $user->update($data);

        session()->flash('message', 'تم تحديث المستخدم بنجاح.');
        $this->reset(['name', 'email', 'password', 'role', 'editingUserId']);
        $this->mount();
    }

    public function delete($id)
    {
        User::destroy($id);
        session()->flash('message', 'تم حذف المستخدم بنجاح.');
        $this->mount();
    }
}
