<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Form extends Component
{
    /**
     * Create a new component instance.
     */
    public $action;
    public $method;
    public $class;
    public $id;
    public $enctype;
    public function __construct($action, $class = '' ,$id='', $method = 'POST', $enctype = '' )
    {
        $this->action = $action;
        $this->class = $class;
        $this->method = strtoupper($method);
        $this->enctype = $enctype;
        $this->id = $id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('partials.form');
    }
}
