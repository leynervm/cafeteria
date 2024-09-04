<?php

namespace App\Http\Livewire\Admin\Users;

use App\Models\Category;
use App\Models\Mesa;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class ShowUsers extends Component
{

    use WithPagination;

    public $open = false;
    public $user;
    public $selectedCategories = [];
    public $selectedMesas = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'user.name' => ['required', 'min:5'],
            'user.email' => ['required', 'email', 'unique:users,email,' . $this->user->id],
            'user.role_id' => ['required', 'exists:roles,id'],
            'selectedCategories' => ['nullable', 'array'],
            'selectedMesas' => ['nullable', 'array']
        ];
    }

    public function mount()
    {
        $this->user = new User();
    }

    public function render()
    {
        $users = User::orderBy('name', 'asc')->paginate();
        $mesas =  Mesa::orderBy('name', 'asc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        $roles = Role::orderBy('name', 'asc')->get();
        return view('livewire.admin.users.show-users', compact('users', 'mesas', 'categories', 'roles'));
    }

    public function updatedOpen()
    {
        // $this->reset(['selectedCategories', 'selectedMesas']);
    }

    public function edit(User $user)
    {
        $this->user = $user;
        $this->selectedCategories = $this->user->categories()->pluck('category_id')->toArray();
        $this->selectedMesas = $this->user->mesas()->pluck('mesa_id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->user->name = $this->user->name;
        $this->user->email = trim($this->user->email);
        $this->user->role_id = trim($this->user->role_id);
        $this->user->password = trim($this->user->password);
        $this->validate();
        $this->user->save();

        $this->user->mesas()->sync($this->selectedMesas);
        $this->user->categories()->sync($this->selectedCategories);
        $this->user->roles()->sync($this->user->role_id);

        $this->emit('updated');
        $this->reset(['open', 'selectedCategories', 'selectedMesas']);
    }

    public function delete(User $user)
    {
        $user->deleteOrFail();
        $this->emit('deleted');
    }
}
