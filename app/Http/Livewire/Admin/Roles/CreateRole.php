<?php

namespace App\Http\Livewire\Admin\Roles;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{

    public $open = false;
    public $name;
    public $selectedPermisos = [];

    protected function rules()
    {
        return [
            'name' => ['required', 'unique:roles,name', 'min:3'],
            'selectedPermisos' => ['required', 'array', 'min:1']
        ];
    }

    public function render()
    {
        // $permisos = Permission::all();
        $permisos = Permission::orderBy('tabla')->get()->groupBy('tabla');
        return view('livewire.admin.roles.create-role', compact('permisos'));
    }

    public function updatingOpen()
    {
        $this->reset(['name', 'selectedPermisos']);
        $this->resetValidation();
    }

    public function save()
    {
        $this->name = mb_strtoupper(trim($this->name), 'UTF-8');
        $this->validate();

        $role = Role::create([
            'name' => $this->name,
        ]);

        $role->permissions()->sync($this->selectedPermisos);
        $this->reset(['open', 'name', 'selectedPermisos']);
        $this->emitTo('admin.roles.show-roles', 'render');
        $this->emit('created');
    }
}
