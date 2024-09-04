<?php

namespace App\Http\Livewire\Admin\Categorias;

use App\Models\Category;
use Livewire\Component;

class CreateCategory extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'unique:categories,name'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.categorias.create-category');
    }

    public function updatingOpen()
    {
        if ($this->open == false) {
            $this->resetValidation();
            $this->reset(['name']);
        }
    }

    public function save()
    {

        $this->name = trim($this->name);
        $this->validate();
        Category::create([
            'name' => $this->name
        ]);

        $this->emitTo('admin.categorias.show-categorias', 'render');
        $this->emit('created');
        $this->reset();
    }
}
