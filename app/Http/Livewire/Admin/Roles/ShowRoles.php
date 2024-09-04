<?php

namespace App\Http\Livewire\Admin\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ShowRoles extends Component
{

    use WithPagination;

    public $open = false;
    public $role;
    public $selectedPermisos = [];

    protected $listeners = ['render'];

    protected function rules()
    {
        return [
            'role.name' => ['required', 'unique:roles,name,' . $this->role->id, 'min:3'],
            'selectedPermisos' => ['required', 'array', 'min:1']
        ];
    }

    public function mount()
    {
        $this->role = new Role();
    }

    public function render()
    {
        $roles = Role::paginate();
        // $permisos = Permission::all();
        $permisos = Permission::orderBy('tabla')->get()->groupBy('tabla');
        return view('livewire.admin.roles.show-roles', compact('roles', 'permisos'));
    }

    public function updatingOpen()
    {
        $this->resetValidation();
    }

    public function edit(Role $role)
    {
        $this->role = $role;
        $this->selectedPermisos = $role->permissions()->pluck('id')->toArray();
        $this->open = true;
    }

    public function update()
    {

        $this->role->name = mb_strtoupper(trim($this->role->name), 'UTF-8');
        $this->validate();
        $this->role->permissions()->sync($this->selectedPermisos);
        $this->role->save();
        $this->open = false;
    }

    public function delete(Role $role)
    {
        $role->destroy($role->id);
        // $this->toast('Eliminado correctamente');
    }
}
