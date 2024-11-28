<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class InputImage extends Component
{
    public $name;
    public $label;
    public $multiple;
    public $required;
    public $disabled;

    /**
     * Create a new component instance.
     */
    public function __construct($name, $label, $multiple = false, $required = false, $disabled = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->multiple = $multiple;
        $this->required = $required;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input-image');
    }
}
