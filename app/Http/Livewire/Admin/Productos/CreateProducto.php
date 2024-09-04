<?php

namespace App\Http\Livewire\Admin\Productos;

use App\Models\Agregado;
use App\Models\Category;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

class CreateProducto extends Component
{

    use WithFileUploads;

    public $open = false;
    public $selectedAgregados = [];
    public $imagen, $identificador;
    public $name, $price, $rendimiento, $category_id;

    protected $rules = [
        'name' => 'required|string|min:3',
        'price' => 'required|numeric|min:0',
        'rendimiento' => 'nullable|numeric|min:0',
        'imagen' => 'nullable',
        'category_id' => 'required|integer|min:1|exists:categories,id',
    ];

    public function render()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $agregados = Agregado::orderBy('name', 'asc')->get();
        return view('livewire.admin.productos.create-producto', compact('categories', 'agregados'));
    }

    public function save()
    {
        $this->validate();
        $pathimagen = null;
        if ($this->imagen) {
            if (!(Storage::exists('images/productos/'))) {
                Storage::makeDirectory('images/productos/');
            }
            $pathimagen = rand() . '.' . $this->imagen->getClientOriginalExtension();
            Storage::putFileAs('images/productos/', $this->imagen, $pathimagen);
        }

        $producto = Producto::create([
            'name' => $this->name,
            'price' => $this->price,
            'rendimiento' => $this->rendimiento,
            'imagen' => $pathimagen,
            'unit' => 'NIU',
            'code' => Str::random(5),
            'deleted' => 0,
            'category_id' => $this->category_id,
            'user_id' => Auth::user()->id,
        ]);

        if (!empty($this->selectedAgregados)) {
            $producto->agregados()->sync($this->selectedAgregados);
        }

        $this->emitTo('admin.productos.show-productos', 'render');
        $this->identificador = rand();
        $this->emit('created');
        $this->reset();
    }
}
