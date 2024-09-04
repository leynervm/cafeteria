<?php

namespace App\Http\Livewire\Admin\Productos;

use App\Models\Agregado;
use App\Models\Category;
use App\Models\Producto;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Nette\Utils\Random;

class ShowProductos extends Component
{

    use WithPagination, WithFileUploads;

    public $open = false;
    public $openimagen = false;

    public $selectedAgregados = [];
    public $producto;
    public $identificador, $imagen;
    public $search = '';
    public $searchcategory = [];

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'producto.name' => ['required', 'string', 'min:3', 'unique:productos,name,' . $this->producto->id],
            'producto.price' => ['required', 'numeric', 'min:0'],
            'producto.rendimiento' => ['nullable', 'numeric', 'min:0'],
            'producto.category_id' => ['required', 'integer', 'min:1', 'exists:categories,id'],
            'producto.imagen' => ['nullable'],
            'imagen' => ['nullable']
        ];
    }

    protected $queryString = [
        'search' => ['except' => ''],
        'searchcategory' => ['except' => []]
    ];

    public function mount()
    {
        $this->producto = new Producto();
    }

    public function render()
    {
        $productos = Producto::orderBy('name', 'asc');
        $categories = Category::orderBy('name', 'asc')->get();
        $agregados = Agregado::orderBy('name', 'asc')->get();

        if (trim($this->search) !== '') {
            $productos->where('name', 'ilike', '%' . $this->search . '%');
        }

        if (count($this->searchcategory)) {
            $productos->whereIn('category_id', $this->searchcategory);
        }

        $productos = $productos->paginate();

        return view('livewire.admin.productos.show-productos', compact('productos', 'categories', 'agregados'));
    }

    public function edit(Producto $producto)
    {
        $this->reset();
        $this->identificador = rand();
        $this->producto = $producto;
        $this->selectedAgregados = $producto->agregados()->pluck('agregados.id')->toArray();
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        if ($this->imagen) {

            if (!(Storage::exists('images/productos/'))) {
                Storage::makeDirectory('images/productos/');
            }

            $pathimagen = rand() . '.' . $this->imagen->getClientOriginalExtension();
            Storage::putFileAs('images/productos/', $this->imagen, $pathimagen);
            $this->producto->imagen = $pathimagen;
        }
        $this->producto->save();
        $this->emit('updated');
        $this->producto->agregados()->sync($this->selectedAgregados);
        $this->resetExcept(['pagination', 'producto']);
    }

    public function delete(Producto $producto)
    {
        $producto->deleteOrFail();
        $this->emit('deleted');
    }

    public function updatingSearchcategory()
    {
        $this->resetPage();
    }

    public function openmodalimagen(Producto $producto)
    {
        $this->reset();
        $this->producto = $producto;
        $this->openimagen = true;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
