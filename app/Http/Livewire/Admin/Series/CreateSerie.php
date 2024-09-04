<?php

namespace App\Http\Livewire\Admin\Series;

use App\Models\Serie;
use App\Rules\ValidateUnique;
use Livewire\Component;

class CreateSerie extends Component
{

    public $open = false;
    public $serie, $code;
    public $contador = 0;

    protected function rules()
    {
        return [
            'serie' => [
                'required', 'string', 'min:4', 'max:4',
                new ValidateUnique('series', 'serie', $this->serie)
            ],
            'code' => [
                'required', 'string', 'min:2', 'max:2'
            ],
            'contador' => [
                'required', 'integer', 'min:0'
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.series.create-serie');
    }

    public function save()
    {
        $this->serie = trim($this->serie);
        $this->code = trim($this->code);
        $name = '';
        $referencia = null;

        if ($this->code == "01") {
            $name = 'FACTURA ELECTRÓNICA';
        } else if ($this->code == '03') {
            $name = 'BOLETA DE VENTA';
        } else {
            $name = 'NOTA DE CREDITO';
            $referencia = substr($this->serie, 0, 1) == "F" || substr($this->serie, 0, 1) == "f" ? '01' : '03';
        }
        $this->validate();
        Serie::create([
            'serie' => $this->serie,
            'name' => $name,
            'code' => $this->code,
            'referencia' => $referencia,
            'contador' => $this->contador
        ]);
        $this->reset();
        $this->emitTo('admin.series.show-series', 'render');
        $this->emit('created');
    }
}
