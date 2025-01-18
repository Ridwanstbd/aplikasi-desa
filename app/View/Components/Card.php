<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    public $subtitle;
    public $image;
    public $footer;
    public $anchor;
    /**
     * Create a new component instance.
     */
    public function __construct($title = '', $subtitle = '', $image = '', $footer = '',$anchor='')
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->image = $image;
        $this->footer = $footer;
        $this->anchor = $anchor;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('partials.card');
    }
}
