<?php

namespace App\Http\Livewire\Admin\Orders;

use App\Models\Mesa;
use App\Models\Order;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ShowOrders extends Component
{

    use WithPagination;

    public $search = '';
    public $searchmesa = [];
    public $searchfecha = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'searchfecha' => ['except' => null, 'except' => ''],
        'searchmesa' => ['except' => []]
    ];

    public function mount()
    {
        // $this->searchfecha = Carbon::now('America/Lima')->format('Y-m-d');
    }

    public function render()
    {

        $orders = Order::orderBy('date', 'desc');
        $mesas = Mesa::orderBy('name', 'asc')->get();

        if (trim($this->search) !== '') {
            $orders->where('id', 'like', $this->search)
                ->orWhere('name', 'ilike', '%' . $this->search . '%');
        }


        if ($this->searchfecha) {
            $orders->whereDate('date', $this->searchfecha);
        }

        if (count($this->searchmesa)) {
            $orders->whereIn('mesa_id', $this->searchmesa);
        }

        $orders = $orders->paginate();

        return view('livewire.admin.orders.show-orders', compact('orders', 'mesas'));
    }

    public function updatingSearchmesa()
    {
        $this->resetPage();
    }

    public function updatingSearchfecha()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
