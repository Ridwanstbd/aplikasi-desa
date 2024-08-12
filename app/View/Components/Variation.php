<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Variation extends Component
{
    /**
     * Create a new component instance.
     */
    public $variations;
    public $disabled;

    public function __construct($variations=[],$disabled=false)
    {
        $this->variations = $variations;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.variation');
    }
}
