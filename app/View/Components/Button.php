<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class Button extends Component
{
    /**
     * Create a new component instance.
     */
    public $id;

    public $type;
    public $label;
    public $class;
    public $disabled;

    public function __construct($id = '', $type = 'button', $label = '', $class = 'btn btn-primary', $disabled = false)
    {
        $this->id = $id;
        $this->type = $type;
        $this->label = $label;
        $this->class = $class;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button');
    }
}
