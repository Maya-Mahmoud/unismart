<?php

namespace App\Livewire\Admin;

use App\Models\Classroom;
use Livewire\Component;

class Classrooms extends Component
{
    public $name;
    public $capacity;
    public $location;
    public $editingClassroomId;

    protected $rules = [
        'name' => 'required|string|max:255',
        'capacity' => 'required|integer',
        'location' => 'nullable|string|max:255',
    ];

    public function render()
    {
        $classrooms = Classroom::all();
        return view('livewire.admin.classrooms', [
            'classrooms' => $classrooms,
        ]);
    }
    

        public function store()
        {
            $this->validate([
                'name' => 'required|string|max:255',
                'capacity' => 'required|integer',
                'location' => 'nullable|string|max:255',
            ]);

            Classroom::create([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'location' => $this->location,
            ]);

            session()->flash('message', 'تم إضافة القاعة بنجاح.');

            $this->reset(['name', 'capacity', 'location']);
        }

        public function edit($id)
        {
            $classroom = Classroom::findOrFail($id);
            $this->editingClassroomId = $classroom->id;
            $this->name = $classroom->name;
            $this->capacity = $classroom->capacity;
            $this->location = $classroom->location;
        }

        public function update()
        {
            $this->validate([
                'name' => 'required|string|max:255',
                'capacity' => 'required|integer',
                'location' => 'nullable|string|max:255',
            ]);

            $classroom = Classroom::findOrFail($this->editingClassroomId);
            $classroom->update([
                'name' => $this->name,
                'capacity' => $this->capacity,
                'location' => $this->location,
            ]);

            session()->flash('message', 'تم تحديث القاعة بنجاح.');

            $this->reset(['name', 'capacity', 'location', 'editingClassroomId']);
        }

        public function delete($id)
        {
            Classroom::destroy($id);
            session()->flash('message', 'تم حذف القاعة بنجاح.');
        }

        public function cancelEdit()
        {
            $this->reset(['name', 'capacity', 'location', 'editingClassroomId']);
        }
    }
    
