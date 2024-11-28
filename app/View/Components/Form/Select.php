<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Closure;

class Select extends Component
{
    public $name;
    public $label;
    public $options;
    public $multiple;
    public $selected;
    public $required;
    public $placeholder;

    public function __construct(
        $name,
        $label = '',
        $options = [],
        $selected = null,
        $multiple = false,
        $required = false,
        $placeholder = 'Pilih Opsi'
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->multiple = $multiple;
        $this->selected = $selected;
        $this->required = $required;
        $this->placeholder = $placeholder;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.select', [
            'mappedOptions' => $this->mapOptions()
        ]);
    }

    private function isSelected($value): bool
    {
        if (is_array($this->selected)) {
            return in_array($value, $this->selected);
        }

        return $value == $this->selected;
    }

    protected function mapOptions()
    {
        $options = $this->options instanceof \Illuminate\Support\Collection
            ? $this->options
            : collect($this->options);

        return $options->map(function ($option) {
            // Jika option adalah array atau object
            if (is_array($option) || is_object($option)) {
                return [
                    'id' => $option['id'] ?? $option->id ?? '',
                    'name' => $option['name'] ?? $option->name ?? '',
                    'selected' => $this->isSelected($option['id'] ?? $option->id ?? ''),
                ];
            }

            // Jika option adalah nilai sederhana
            return [
                'id' => $option,
                'name' => $option,
                'selected' => $this->isSelected($option),
            ];
        })->values()->toArray();
    }
}
