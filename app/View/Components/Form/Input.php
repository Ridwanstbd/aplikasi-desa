<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class Input extends Component
{
    public $type;
    public $name;
    public $label;
    public $value;
    public $placeholder;
    public $required;
    public $disabled;

    public function __construct(
        $type,
        $name,
        $label = null,
        $value = '',
        $placeholder = '',
        $required = false,
        $disabled = false,
    ) {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label;
        $this->value = old($name, $value);
        $this->placeholder = $placeholder;
        $this->required = $required;
        $this->disabled = $disabled;
    }

    public function isHidden(): bool
    {
        return $this->type === 'hidden';
    }

    public function render(): View|Closure|string
    {
        return view('components.form.input');
    }
}
