<?php

namespace App\Livewire\Dashboard;

use App\Models\User;
use Livewire\Component;

class UserRolePermission extends Component
{
    public User $user;

    public array $roles = [];
    public array $permissions = [];

    public function mount(int $userId)
    {
        $this->user = User::findOrFail($userId);

        $this->roles = $this->user->roles->pluck('name')->toArray();
        $this->permissions = $this->user->permissions->pluck('name')->toArray();
    }

    public function save()
    {
        // Role sync
        $this->user->syncRoles($this->roles);

        // Permission sync (langsung ke user)
        $this->user->syncPermissions($this->permissions);

        session()->flash('success', 'User permissions updated');
    }

    public function render()
    {
        return view('livewire.dashboard.user-role-permission');
    }
}
