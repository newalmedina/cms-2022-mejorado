<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TitleBanner extends Component
{
    public $page_title = '';
    public $banner = '';
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($pageTitle, $banner)
    {
        $this->page_title = $pageTitle;
        $this->banner = $banner;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.title-banner');
    }
}
