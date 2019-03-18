<?php

namespace GreenBar\MenuBuilder\Services;

class MenuBuilder
{
    public static function render_menu($menu_position, $classes)
    {
        $menu_class = config('menu_builder.models.menu');
        $menu_item_class = config('menu_builder.models.menu_item');

        $menu = $menu_class::where('position', $menu_position)->first();
        $menu_items = $menu_item_class::scoped([ 'menu_id' => $menu->id ])->withDepth()->defaultOrder()->get()->toTree();
        $menu_items = $menu_items->first()->children;

        return self::build_nav_ul($menu_items, $classes);
    }

    protected static function build_nav_ul($menu_items, $ul_classes = null, $dropdown_menu = false)
    {
        if (count($menu_items) == 0) {
            return '';
        }

        if ($menu_items->first()->lft === 2) {
            $result  = '<ul class="'. $ul_classes .'">' . PHP_EOL;
        } else {
            if ($dropdown_menu) {
                $result = '<div class="dropdown-menu">' . PHP_EOL;
            } else {
                $result = '<ul>' . PHP_EOL;
            }
        }

        foreach($menu_items as $menu_item) {
            if ($dropdown_menu) {
                if ($menu_item->is_divider) {
                    $result .= '<div class="dropdown-divider"></div>';
                } else {
                    $result .= self::build_nav_link($menu_item, 'dropdown-item');
                }
            } else {
                $li_classes = 'nav-item';
                if (count($menu_item->children) > 0) {
                    $li_classes .= ' dropdown';
                }

                $result .= '<li class="'. $li_classes .'">';

                $result .= self::build_nav_link($menu_item, 'nav-link');
                $result .= self::build_nav_ul($menu_item->children, null, $sub_menu = true);

                $result .= '</li>' . PHP_EOL;
            }
        }

        if ($dropdown_menu) {
            $result .= '</div>' . PHP_EOL;
        } else {
            $result .= '</ul>' . PHP_EOL;
        }

        return $result;
    }

    protected static function build_nav_link($menu_item, $classes = '')
    {
        $link = '<a class="'. $classes .'"';

        if (empty($menu_item->url)) {
            $link .= ' href="#"';
        } else {
            $link .= ' href="'. $menu_item->url .'"';
        }

        if (!empty($menu_item->title)) {
            $link .= ' title="'. $menu_item->title .'"';
        }

        if (!empty($menu_item->target)) {
            $link .= ' target="'. $menu_item->target .'"';
        }

        $link .= '>';

        if (!empty($menu_item->icon)) {
            $link .= '<i class="'. $menu_item->icon .'" aria-hidden="true"></i>';
        }

        $link .= $menu_item->name;

        $link .= '</a>';

        return $link;
    }
}
