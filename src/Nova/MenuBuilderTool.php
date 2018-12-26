<?php

namespace GreenBar\MenuBuilder\Nova;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool as BaseTool;

class MenuBuilderTool extends BaseTool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        //return view('nova-log-viewer::navigation');
    }
}
