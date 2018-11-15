<?php

namespace GreenBar\MenuBuilder\Services;

class MenuBuilder
{
    public static function render_menu($menu_position, $extra_class)
    {
        $menu_class = config('menu_builder.models.menu');
        $menu_item_class = config('menu_builder.models.menu_item');
        
        $menu = $menu_class::where('position', $menu_position)->first();
        //$menu_items = $menu_item_class::scoped([ 'menu_id' => $menu->id ])->withDepth()->get();
        $parent_menu_item = $menu_item_class::where('menu_id', $menu->id)->whereNull('parent_id')->first();

        //pr($parent_menu_item->toArray(),1);

        // $data = compact([
        //     'menu',
        //     'menu_items',
        //     'extra_class',
        // ]);

        // return view('menu_builder::main_navigation', $data)->render();

        //pr($menu_items->toArray(),1);
        
        // $the_tree  = '';//'<ul style="color:#000;">' . PHP_EOL;
        // $the_tree .= self::renderTreeRecursive($menu_items->toArray());

        pr(self::dostuff($parent_menu_item), 1);

        // pr($the_tree,1);
        
        return $the_tree;
    }

    public static function dostuff($menu_item, $close_html = '</ul>')
    {
        $result = '';
        
        if ($menu_item->parent_id == null) {
            $result .= '<ul style="color:#F00; font-size: 20px;">';
        }

        if(count($menu_item->descendants)) {
            foreach($menu_item->descendants as $child_node) {
                $result .= '<li>' . $menu_item->name;

                if (count($menu_item->descendants)) {
                    $result .= self::dostuff($menu_item, '</li>');
                } else {
                    $result .= '</li>';
                }
            }
        } else {
            $result .= $close_html;
        }

        return $result;
    }

    protected static function renderTreeRecursive($tree, $current_depth = -1) 
    {
        $result = '';
        $current_node = array_shift($tree);
        $current_node_depth = $currNode['depth'];

        
        //$result .= '<!-- NEW LOOP current depth: '. $currDepth .' -->' . PHP_EOL;

        // $result .= '<!-- $currDepth: ' . $currDepth . '-->' . PHP_EOL;
        // $result .= '<!-- $currNode["depth"]: ' . $currNode["depth"] . '-->' . PHP_EOL;

        if ($current_node_depth > $currDepth) { // Going down?
            // Yes, prepend <ul>
            if ($currDepth == -1) {
                $result .= '<ul style="color:#000; font-size: 20px;">' . PHP_EOL;
            } else {
                $result .= PHP_EOL . '<ul>' . PHP_EOL;
            }
        } else if ($current_node_depth < $currDepth) { // Going up?
            // Yes, close n open <ul>
            //$result .= str_repeat(('</ul>' . PHP_EOL), $currDepth - $currNode['depth']);
            
            $result .= '</ul>' . PHP_EOL;
            $result .= '</li><!-- * -->' . PHP_EOL;
            
        } else {
            $result .= '</li>' . PHP_EOL.PHP_EOL;
        }
        
        // Always add the node
        $result .= '<li>' . $currNode['name'];

        // Anything left?
        if (!empty($tree)) {
            // Yes, recurse

            $the_depth = $currNode['depth'];
            if ($currNode['depth'] < $tree[0]['depth']) {
                $result .= '</li>';
            }
            
            $result .=  self::renderTreeRecursive($tree, $the_depth);
        } else {
            // No, close remaining <ul>
            $result .= PHP_EOL;
            $result .= str_repeat(('</ul>' . PHP_EOL), $currNode['depth'] + 1);
        }

        return $result;
    }
}
