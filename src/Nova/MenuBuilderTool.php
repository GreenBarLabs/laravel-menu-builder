<?php

namespace GreenBar\MenuBuilder\Nova;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
#use Laravel\Nova\Tool as BaseTool;
use Laravel\Nova\ResourceTool;

class MenuBuilderTool extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Menu Items';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'menu-item-tree';
    }
}
