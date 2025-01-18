<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Carousel extends Component
{
    public $id;
    public $images;
    public $interval;
    /**
     * Create a new component instance.
     */
    public function __construct($id,$images,$interval=3000)
    {
        $this->id = $id;
        $this->images = $images;
        $this->interval = $interval;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.carousel');
    }
}
