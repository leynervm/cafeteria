<?php

namespace App\Http\Livewire\Admin\Locations;

use App\Models\Location;
use App\Rules\ValidateUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowLocations extends Component
{

    use WithPagination;

    public $location;
    public $open = false;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'location.name' => [
                'required', 'string', 'min:3',
                new ValidateUnique('locations', 'name', $this->location->name, $this->location->id)
            ]
        ];
    }

    public function mount()
    {
        $this->location = new Location();
    }

    public function render()
    {

        $locations = Location::orderBy('name', 'asc')->paginate();
        return view('livewire.admin.locations.show-locations', compact('locations'));
    }

    public function edit(Location $location)
    {
        $this->resetValidation();
        $this->location = $location;
        $this->open = true;
    }

    public function update()
    {

        $this->location->name = trim($this->location->name);
        $this->validate();
        $this->location->save();
        $this->emit('updated');
        $this->reset();
        // dd($this->location);
    }

    public function delete(Location $location)
    {
        $location->deleteOrFail();
        $this->emit('deleted');
    }
}
