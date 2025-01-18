<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MarketplaceButton extends Component
{
    public $url;
    public $type;
    public $name;
    /**
     * Create a new component instance.
     */
    public function __construct($url,$type, $name)
    {
        $this->url = $url;
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.marketplace-button');
    }
}
