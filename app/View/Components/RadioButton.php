<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RadioButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $id, $name, $value, $text, $active;

    public function __construct($id, $name, $value, $text, $active = false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->value = $value;
        $this->text = $text;
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.radio-button');
    }
}
