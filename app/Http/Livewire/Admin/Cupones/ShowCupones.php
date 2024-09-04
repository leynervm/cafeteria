<?php

namespace App\Http\Livewire\Admin\Cupones;

use App\Models\Coupon;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCupones extends Component
{

    use WithPagination;

    public $open = false;
    public $coupon;

    public $search = '';
    public $searchestado = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'searchestado' => ['except' => ''],
    ];

    protected $listeners = ['render', 'delete'];

    protected $rules = [
        'coupon.code' => 'required',
        'coupon.descuento' => 'required',
        'coupon.limit' => 'nullable',
        'coupon.start' => 'required',
        'coupon.end' => 'required',
    ];

    public function render()
    {
        $cupones = Coupon::orderby('end', 'desc');

        if (trim($this->search) !== '') {
            $cupones->where('code', 'ilike', '%' . $this->search . '%');
        }

        if (trim($this->searchestado) !== '') {
            $cupones->where('status', $this->searchestado);
        }

        $cupones = $cupones->paginate();
        return view('livewire.admin.cupones.show-cupones', compact('cupones'));
    }

    public function edit(Coupon $coupon)
    {
        $this->coupon = $coupon;
        $this->coupon->start = Carbon::parse($this->coupon->start)->format('Y-m-d');
        $this->coupon->end = Carbon::parse($this->coupon->end)->format('Y-m-d');
        $this->open = true;
    }

    public function update()
    {
        $this->validate();
        $this->coupon->save();
        $this->emit('updated');
        $this->reset();
    }

    public function delete(Coupon $coupon)
    {
        $coupon->deleteOrFail();
        $this->emit('deleted');
    }
}
