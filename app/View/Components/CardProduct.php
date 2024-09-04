<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CardProduct extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $default;

    public function __construct($default = false)
    {
        $this->default = $default;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.card-product');
    }
}
