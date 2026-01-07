<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Spatie\Permission\Models\Permission;


class PermissionIndex extends Component
{
    public $name = '';
    public $showDeleteModal = false;
    public $deleteId;
    public $deleteName;

    public function create()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create(['name' => $this->name]);

        $this->reset('name');
        session()->flash('success', 'Permission created');
    }

    public function confirmDelete(int $id)
    {
        $permission = Permission::findOrFail($id);
        $this->deleteId = $permission->id;
        $this->deleteName = $permission->name;
        $this->showDeleteModal = true;
        
    }

    public function delete()
    {
        $permission = Permission::findOrFail($this->deleteId);
        $permission->delete();
        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);

    }

    public function render()
    {
          return view('livewire.dashboard.permission-index', [
            'permissions' => Permission::orderBy('name')->get(),
        ])->layout('layouts.dashboard.main');
    }
}
