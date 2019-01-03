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
        $menu_items = $this->menu_item_class::scoped([ 'menu_id' => $menu_id ])->withDepth()->get()->toTree();
        $menu_items = $menu_items->first()->children;

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $menu_items,
        ], 200);
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
        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => [],
        ], 200);
    }

    public function get_menu_item(Request $request, $menu_item_id)
    {
        $menu_item = $this->menu_item_class::find($menu_item_id);

        if (!$menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu Item does not exist.',
                ],
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $menu_item->toArray(),
        ], 200);
    }

    public function create_menu_item(Request $request, $menu_id)
    {
        $request->validate([
            'name' => 'required_unless:is_divider,1',
            'is_divider' => 'boolean',
            'url' => 'present',
        ], [
            'name.required_unless' => 'A name is required unless the menu item is a divider.',
        ]);

        // Doing this because it screwed up my text editor's syntax highlighting. -Joel Haker
        $menu_class = $this->menu_class;
        $menu_item_class = $this->menu_item_class;

        $menu = $menu_class::where('menu_id', $menu_id)->first();
        if (!$menu) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu not found.',
                ],
                'data' => [],
            ], 422);
        }

        $menu_item = $menu_item_class::where('menu_id', $menu_id)->whereNull('parent_id')->first();
        if (!$menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Parent menu item not found.',
                ],
                'data' => [],
            ], 422);
        }

        $menu_item_name = $request->input('name');
        if ((int)$request->input('is_divider')) {
            $menu_item_name = '---';
        }

        $parent_menu_item::create([
            'menu_id' => $menu->id,
            'parent_id' => $menu_item->id,
            'name' => $menu_item_name,
            'is_divider' => (int)$request->input('is_divider'),
            'url' => ($request->filled('url')) ? $request->input('url') : null,
        ]);

        $menu_item_class::scoped([ 'menu_id' => $menu->id ])->fixTree();

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => [],
        ], 200);
    }

    public function update_menu_item(Request $request, $menu_item_id)
    {
        $request->validate([
            'name' => 'required_unless:is_divider,1',
            'is_divider' => 'boolean',
            'url' => 'present',
        ], [
            'name.required_unless' => 'A name is required unless the menu item is a divider.',
        ]);

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
            ], 422);
        }

        $menu_item_name = $request->input('name');
        $url = ($request->filled('url')) ? $request->input('url') : null;
        if ((int)$request->input('is_divider')) {
            $menu_item_name = '---';
            $url = null;
        }

        $menu_item->fill([
            'name' => $menu_item_name,
            'is_divider' => (int)$request->input('is_divider'),
            'url' => $url,
        ]);
        $menu_item->save();

        $menu_item_class::scoped([ 'menu_id' => $menu_item->menu_id ])->fixTree();

        return response()->json([
            'status' => 'success',
            'messages' => [],
            'data' => $menu_item->toArray(),
        ], 200);
    }

    public function delete_menu_item(Request $request, $menu_item_id)
    {
        $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
        ]);

        if ($menu_item_id != $request->input('menu_item_id')) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu item not found.',
                ],
                'data' => [],
            ], 422);
        }

        // Doing this because it screwed up my text editor's syntax highlighting. -Joel Haker
        $menu_item_class = $this->menu_item_class;
        $menu_item = $menu_item_class::find($request->input('menu_item_id'));

        if (!$menu_item) {
            return response()->json([
                'status' => 'error',
                'messages' => [
                    'Menu item not found.',
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
