<?php

namespace GreenBar\MenuBuilder\Nova\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class MenuBuilderController extends BaseController
{
    /**
     * The Menu Class the app is using
     *
     * @var GreenBar\MenuBuilder\Models\Menu
     */
    private $menu_class;

    /**
     * The MenuItem Class the app is using
     *
     * @var GreenBar\MenuBuilder\Models\MenuItem
     */
    private $menu_item_class;

    /**
     * The data to output to the view
     *
     * @var array
     */
    private $data = [];

    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menu_class = config('menu_builder.models.menu');
        $this->menu_item_class = config('menu_builder.models.menu_item');
    }

    /**
     * Utility Functions
     */

    private function set_page_data(array $page_data)
    {
        $this->data['page_data'] = $page_data;
    }

    /**
     * MenuItem Operations
    **/

    /**
     * List menu items for a particular menu
     *
     * @param  Request  $request
     * @param  Integer  $menu_id
     * @return JsonResponse
     */
    public function list_menu_items(Request $request, $menu_id)
    {
        $menu_items = $this->menu_item_class::scoped([ 'menu_id' => $menu_id ])->withDepth()->defaultOrder()->get()->toTree();
        $menu_items = $menu_items->first()->children->toArray();

        if (count($menu_items) == 0) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu items not found.'
                ],
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $menu_items,
        ], 200);
    }

    private function build_menu_order(array &$new_sort_array, array $sort_array, int $menu_id, $parent_menu_item_id = null) {
        for ($i = 0; $i < count($sort_array); $i++) {
            $new_sort_array[$i] = [
                'id' => $sort_array[$i]['id'],
                'menu_id' => $menu_id,
                'parent_id' => $parent_menu_item_id,
                'name' => $sort_array[$i]['name'],
                'lft' => null,
                'rgt' => null,
                'children' => [],
            ];

            if (!empty($sort_array[$i]['children'])) {
                $this->build_menu_order($new_sort_array[$i]['children'], $sort_array[$i]['children'], $menu_id, $sort_array[$i]['id']);
            }
        }
    }

    /**
     * Save the new menu order
     *
     * @param  Request  $request
     * @param  Integer   $menu_id
     * @return JsonResponse
     */
    public function save_menu_order(Request $request, $menu_id)
    {
        if (!$request->filled('sort_order')) {
            return response()->json([
                'status' => 'success',
                'messages' => [
                    'A Sort Order is required.'
                ],
                'data' => [],
            ], 422);
        }

        $sort_order = json_decode($request->input('sort_order'),1);

        if (!$sort_order || !is_array($sort_order)) {
            return response()->json([
                'status' => 'success',
                'messages' => [
                   'A Sort Order is required.'
                ],
                'data' => [],
            ], 422);
        }

        $root_node = $this->menu_item_class::scoped([ 'menu_id' => $menu_id ])->withDepth()->get()->toTree();
        $root_node = $root_node->first();
        $new_sort_order = [];
        $new_sort_order[] = [
            'id' => $root_node->id,
            'parent_id' => $root_node->parent_id,
            'name' => $root_node->name,
            'lft' => null,
            'rgt' => null,
            'children' => [],
        ];

        $this->build_menu_order($new_sort_order[0]['children'], $sort_order, $menu_id, $new_sort_order[0]['id']);
        $this->menu_item_class::rebuildTree($new_sort_order);

        return $this->list_menu_items($request, $menu_id);
    }

    public function get_menu_item(Request $request, $menu_item_id)
    {
        $menu_item = $this->menu_item_class::find($menu_item_id);

        if (!$menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu Item not found.',
                ],
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $menu_item->toArray(),
        ], 200);
    }

    public function store_menu_item(Request $request, $menu_id)
    {
        $request->validate([
            'menu_id' => 'required',
            'name' => 'required_unless:is_divider,1',
            'is_divider' => 'boolean',
            'url' => 'present',
        ], [
            'name.required_unless' => 'A name is required unless the menu item is a divider.',
        ]);

        // Doing this because it screwed up my text editor's syntax highlighting. -Joel Haker
        $menu_class = $this->menu_class;
        $menu_item_class = $this->menu_item_class;

        //pr($request->input('menu_id'),1);
        $menu = $menu_class::find((int) $request->input('menu_id'));

        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu not found.',
                ],
                'data' => [],
            ], 404);
        }

        $parent_menu_item = $menu_item_class::where('menu_id', $menu->id)->whereNull('parent_id')->first();

        if (!$parent_menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Parent menu item not found.',
                ],
                'data' => [],
            ], 404);
        }

        $menu_item_name = $request->input('name');
        $menu_item_url = ($request->filled('url')) ? $request->input('url') : null;

        if ((int)$request->input('is_divider')) {
            $menu_item_name = '---';
            $menu_item_url = null;
        }

        $new_menu_item = $menu_item_class::create([
            'menu_id' => $menu->id,
            'parent_id' => $parent_menu_item->id,
            'name' => $menu_item_name,
            'is_divider' => (int)$request->input('is_divider'),
            'url' => $menu_item_url,
        ]);

        $menu_item_class::scoped([ 'menu_id' => $menu->id ])->fixTree();

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $new_menu_item->toArray(),
        ], 200);
    }

    public function update_menu_item(Request $request, $menu_id, $menu_item_id)
    {
        $request->validate([
            'name' => 'required_unless:is_divider,1',
            'is_divider' => 'boolean',
            'url' => 'present',
        ], [
            'name.required_unless' => 'A name is required unless the menu item is a divider.',
        ]);

        // Doing this because it fixed my text editors syntax highlighting from being screwed up. -Joel
        $menu_item_class = $this->menu_item_class;
        $menu_item = $menu_item_class::find($menu_item_id);

        if (!$menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu item not found.',
                ],
                'data' => [],
            ], 404);
        }

        $menu_item_name = $request->input('name');
        $menu_item_url = ($request->filled('url')) ? $request->input('url') : null;

        if ((int)$request->input('is_divider')) {
            $menu_item_name = '---';
            $menu_item_url = null;
        }

        $menu_item->fill([
            'name' => $menu_item_name,
            'is_divider' => (int)$request->input('is_divider'),
            'url' => $menu_item_url,
        ]);
        $menu_item->save();

        $menu_item_class::scoped([ 'menu_id' => $menu_item->menu_id ])->fixTree();

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $menu_item->toArray(),
        ], 200);
    }

    public function delete_menu_item(Request $request, $menu_id, $menu_item_id)
    {
        // Doing this because it screwed up my text editor's syntax highlighting. -Joel Haker
        $menu_item_class = $this->menu_item_class;
        $menu_item = $menu_item_class::find($menu_item_id);

        if (!$menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu item not found.',
                ],
                'data' => [],
            ], 404);
        }

        if ($menu_item->menu_id != $menu_id) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Can not delete menu item.',
                ],
                'data' => [],
            ], 422);
        }

        $menu_item->forceDelete();

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => [],
        ], 200);
    }
}
