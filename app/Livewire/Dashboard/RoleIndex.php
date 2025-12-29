<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public string $name = '';

    public function create()
    {
        $this->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create(['name' => $this->name]);

        $this->reset('name');
        session()->flash('success', 'Role created');
    }

    public function delete(int $id)
    {
        Role::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('livewire.dashboard.role-index', [
            'roles' => Role::withCount('users')->get()
        ])->layout('layouts.dashboard.main');
    }
}
