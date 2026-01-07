<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleIndex extends Component
{
    public bool $showPermissionsModal = false;

    public string $name = '';

    public ?int $selectedRoleId = null;
    public array $selectedPermissions = [];

    public $showDeleteModal = false;
    public $deleteId;
    public $deleteName;

    public function create()
    {
        $this->validate([
            'name' => 'required|unique:roles,name'
        ]);

        Role::create(['name' => $this->name]);

        $this->reset('name');
        session()->flash('success', 'Role created');
    }

   public function editPermissions(int $roleId)
    {
        $role = Role::findOrFail($roleId);

        $this->selectedRoleId = $role->id;
        $this->selectedPermissions = $role
            ->permissions
            ->pluck('name')
            ->toArray();

        $this->showPermissionsModal = true;
    }


    public function savePermissions()
    {
        $role = Role::findOrFail($this->selectedRoleId);
        $role->syncPermissions($this->selectedPermissions);

        session()->flash('success', 'Permissions updated');
        $this->showPermissionsModal = false;
    }

    public function confirmDelete(int $id)
    {
        $role = Role::findOrFail($id);
        $this->deleteId = $role->id;
        $this->deleteName = $role->name;
        $this->showDeleteModal = true;
        
    }

    public function delete()
    {
        $role = Role::findOrFail($this->deleteId);
        $role->delete();
        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);
    }

    public function render()
    {
        return view('livewire.dashboard.role-index', [
            'roles' => Role::withCount('users')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ])->layout('layouts.dashboard.main');
    }
}
