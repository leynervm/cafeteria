<?php

namespace App\Http\Livewire\Admin\Cupones;

use App\Models\Coupon;
use Livewire\Component;

class CreateCupon extends Component
{
    public $open = false;
    public $code, $limit, $start, $end;
    public $descuento;

    protected $rules = [
        'code' => 'required',
        'descuento' => 'required',
        'limit' => 'nullable',
        'start' => 'required',
        'end' => 'required',
    ];

    public function render()
    {
        return view('livewire.admin.cupones.create-cupon');
    }

    public function save()
    {

        $this->validate();
        Coupon::create([
            'code' => $this->code,
            'descuento' => $this->descuento,
            'limit' => $this->limit,
            'start' => $this->start,
            'end' => $this->end,
            'status' => 0
        ]);

        $this->emitTo('admin.cupones.show-cupones', 'render');
        $this->emit('created');
        $this->reset();
    }
}
