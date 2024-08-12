<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Create a new component instance.
     */
    public $name;
    public $label;
    public $checked;
    public $disabled;
    public $hidden;

    public function __construct($name, $label = '', $checked, $disabled= false,$hidden=false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->checked = $checked;
        $this->disabled = $disabled;
        $this->hidden = $hidden;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.checkbox');
    }
}
