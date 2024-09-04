<?php

namespace App\Http\Livewire\Admin\Categorias;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCategorias extends Component
{

    use WithPagination;
    public $open = false;
    public $category;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'category.name' => ['required', 'min:3', 'unique:categories,name,' . $this->category->id],
        ];
    }

    public function mount()
    {
        $this->category = new Category();
    }

    public function render()
    {
        $categories = Category::orderBy('name', 'asc', 0)->paginate();
        return view('livewire.admin.categorias.show-categorias', compact('categories'));
    }

    public function edit(Category $category)
    {
        $this->resetValidation();
        $this->category = $category;
        $this->open = true;
    }

    public function update()
    {

        $this->category->name = trim($this->category->name);
        $this->validate();
        $this->category->save();
        $this->emit('updated');
        $this->reset();
    }

    public function delete(Category $category)
    {
        $category->deleteOrFail();
        $this->emit('deleted');
    }
}
