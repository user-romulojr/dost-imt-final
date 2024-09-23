<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class FilterButton extends Component
{
    public $route;
    public $selectLabels;
    public $selectFields;
    /**
     * Create a new component instance.
     */
    public function __construct($route, $selectLabels, $selectFields)
    {
        $this->route = $route;
        $this->selectLabels = $selectLabels;
        $this->selectFields = $selectFields;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.filter-button');
    }
}
