<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Category;
use App\Models\Mesa;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateUser extends Component
{

    public $open = false;
    public $name, $email, $role_id, $password, $password_confirmation;
    public $selectedCategories = [];
    public $selectedMesas = [];

    protected function rules()
    {
        return [
            'name' => ['required', 'min:5'],
            'email' => ['required', 'email', 'unique:users,email'],
            'role_id' => ['required', 'exists:roles,id'],
            'password' => ['required', 'min:8', 'max:32', 'confirmed'],
            'selectedCategories' => ['nullable', 'array'],
            'selectedMesas' => ['nullable', 'array']
        ];
    }

    public function render()
    {
        $mesas = Mesa::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $roles = Role::all();
        return view('livewire.admin.users.create-user', compact('mesas', 'categories', 'roles'));
    }

    public function updatingOpen()
    {
        $this->reset([
            'name', 'email', 'role_id', 'password', 'password_confirmation',
            'selectedCategories', 'selectedMesas'
        ]);
        $this->resetValidation();
    }

    public function save()
    {
        $this->name = trim($this->name);
        $this->email = trim($this->email);
        $this->role_id = trim($this->role_id);
        $this->password = trim($this->password);
        $this->validate();

        $user = User::create([
            'name' => trim($this->name),
            'email' => trim($this->email),
            'role_id' => trim($this->role_id),
            'password' => bcrypt($this->password),
            'status' => 0
        ]);

        $user->mesas()->sync($this->selectedMesas);
        $user->categories()->sync($this->selectedCategories);
        $user->roles()->sync($this->role_id);

        $this->reset([
            'name', 'email', 'role_id', 'password',
            'password_confirmation', 'open', 'selectedCategories', 'selectedMesas'
        ]);
        $this->emitTo('admin.users.show-users', 'render');
        $this->emit('created');
    }
}
