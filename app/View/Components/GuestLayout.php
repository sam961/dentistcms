<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class GuestLayout extends Component
{
    /**
     * The tenant instance.
     *
     * @var \App\Models\Tenant|null
     */
    public $tenant;

    /**
     * Create a new component instance.
     */
    public function __construct($tenant = null)
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
