<?php

namespace App\Http\Livewire\Admin\Permisos;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Livewire\WithPagination;

class ShowPermisos extends Component
{
    use WithPagination;

    public function render()
    {
        $permisos = Permission::orderBy('tabla')->get()->groupBy('tabla');
        return view('livewire.admin.permisos.show-permisos', compact('permisos'));
    }
}
