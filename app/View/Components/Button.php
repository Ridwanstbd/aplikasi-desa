<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     */
    public $type;
    public $label;
    public $class;
    public $disabled;

    public function __construct($type = 'button', $label = '', $class = 'btn btn-primary', $disabled = false)
    {
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
