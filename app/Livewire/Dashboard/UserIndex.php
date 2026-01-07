<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserIndex extends Component
{
    public $users;
    public $roles;

    public $userId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId;
    public $deleteName;


    protected function rules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'password' => $this->userId ? 'nullable|min:6' : 'required|min:6',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function mount()
    {
        $this->roles = Role::pluck('name');
        $this->loadUsers();
    }

    public function loadUsers()
    {
        $this->users = User::with('roles')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id)
    {
        $user = User::with('roles')->findOrFail($id);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';

        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $user = User::updateOrCreate(
            ['id' => $this->userId],
            [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
                    ? Hash::make($this->password)
                    : User::find($this->userId)?->password,
            ]
        );

        $user->syncRoles([$this->role]);

        $this->resetForm();
        $this->loadUsers();
        $this->showModal = false;
    }

    public function confirmDelete(int $id)
    {
        $user = User::findOrFail($id);
        $this->deleteId = $user->id;
        $this->deleteName = $user->name;
        $this->showDeleteModal = true;
        
    }
    
    public function delete()
    {
        $user = User::findOrFail($this->deleteId);
        $user->delete();
        $this->loadUsers();
        $this->reset(['deleteId', 'deleteName', 'showDeleteModal']);

    }

    private function resetForm()
    {
        $this->reset(['userId', 'name', 'email', 'password', 'role']);
    }

    public function render()
    {
        return view('livewire.dashboard.user-index')
            ->layout('layouts.dashboard.main');
    }
}
