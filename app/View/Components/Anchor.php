<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Anchor extends Component
{
    /**
     * Create a new component instance.
     */
    public $href;
    public $label;
    public function __construct($href,$label)
    {
        $this->href = $href;
        $this->label = $label;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.anchor');
    }
}
