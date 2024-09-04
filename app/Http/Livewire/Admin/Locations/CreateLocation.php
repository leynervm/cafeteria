<?php

namespace App\Http\Livewire\Admin\Locations;

use App\Models\Location;
use App\Rules\ValidateUnique;
use Livewire\Component;

class CreateLocation extends Component
{

    public $open = false;
    public $name;

    protected function rules()
    {
        return [
            'name' => [
                'required', 'string', 'min:3',
                new ValidateUnique('locations', 'name', $this->name)
            ]
        ];
    }

    public function render()
    {
        return view('livewire.admin.locations.create-location');
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
        Location::create([
            'name' => $this->name
        ]);

        $this->emitTo('admin.locations.show-locations', 'render');
        $this->emit('created');
        $this->reset();
    }
}
