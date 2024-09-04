<?php

namespace App\Http\Livewire;

use App\Events\MessageSent;
use Livewire\Component;

class FormChat extends Component
{

    protected $listeners = ['echo:chat,MessageSent' => 'escuchar'];

    public $message;

    public function render()
    {
        return view('livewire.form-chat');
    }

    public function save()
    {
        broadcast(new MessageSent($this->message));
    }

    public function escuchar()
    {

        $this->emit('messageAdded', $this->message);
        // $this->emit('render');
        // return dd($this->message);
    }
}
