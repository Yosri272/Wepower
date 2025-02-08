<?php

namespace App\View\Components;

use App\Models\Brand;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BrandEditModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Brand $brand
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.brand-edit-modal');
    }
}
