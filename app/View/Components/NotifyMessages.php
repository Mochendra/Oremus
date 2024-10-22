<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NotifyMessages extends Component
{
    public $messages;

    public function __construct($messages = [])
    {
        $this->messages = $messages;
    }

    public function render()
    {
        return view('components.notify-messages');
    }
}
