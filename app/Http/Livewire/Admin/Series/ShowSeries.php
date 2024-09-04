<?php

namespace App\Http\Livewire\Admin\Series;

use App\Models\Serie;
use App\Rules\ValidateUnique;
use Livewire\Component;
use Livewire\WithPagination;

class ShowSeries extends Component
{

    use WithPagination;

    public $open = false;
    public $serie;

    protected $listeners = ['render', 'delete'];

    protected function rules()
    {
        return [
            'serie.serie' => [
                'required', 'string', 'min:4', 'max:4',
                new ValidateUnique('series', 'serie', $this->serie->serie, $this->serie->id)
            ],
            'serie.code' => [
                'required', 'string', 'min:2', 'max:2'
            ],
            'serie.contador' => [
                'required', 'integer', 'min:0'
            ],
        ];
    }

    public function mount()
    {
        $this->serie = new Serie();
    }

    public function render()
    {

        $series = Serie::orderBy('code', 'asc')->paginate();
        return view('livewire.admin.series.show-series', compact('series'));
    }

    public function edit(Serie $serie)
    {
        $this->serie = $serie;
        $this->open = true;
    }

    public function update()
    {
        $this->serie->name = trim($this->serie->name);
        $this->validate();
        $this->serie->save();
        $this->resetValidation();
        $this->reset(['open']);
        $this->emit('updated');
    }

    public function delete(Serie $serie)
    {
        $serie->deleteOrFail();
        $this->emit('delete');
    }
}
