<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomModal extends Component
{
    public $id;
    public $action;
    public $method;
    public $title;
    public $size;

    /**
     * Create a new component instance.
     */
    public function __construct($id, $action, $method = 'POST', $title = '', $size = 'modal-md')
    {
        $this->id = $id;
        $this->action = $action;
        $this->method = strtoupper($method);
        $this->title = $title;
        $this->size = $size;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.custom-modal');
    }
}
