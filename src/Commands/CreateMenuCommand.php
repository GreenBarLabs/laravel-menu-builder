<?php
namespace GreenBar\MenuBuilder\Commands;

use Illuminate\Console\Command;

class CreateMenuCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menus:create-menu {position} {--menu-items=0}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a menu with GreenBar\\MenuBuilder';

    /**
     * Should we enable debug messaging?
     *
     * @var boolean
     */
    protected $debug_messaging = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $menu_class = config('menu_builder.models.menu');
        $menu_item_class = config('menu_builder.models.menu_item');

        $menu_postition = $this->argument('position');
        $menu_items_to_stub = (int) $this->option('menu-items');

        try {
            $menu = $menu_class::create([
                'position' => $menu_postition,
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error('You have already created a menu with the position of "' . $menu_postition . '"');
            $this->warning($e->getMessage());
            return 1;
        }


        $child_menu_items = [];
        if ($menu_items_to_stub > 0) {
            for ($i = 0; $i < $menu_items_to_stub; $i++) {
                $child_menu_items[] = [
                    'menu_id' => $menu->id,
                    'name' => 'Parent ' . $menu->position . ' Menu Item ' . $i,
                ];
            } 
        }

        // Create the parent node menu item
        $parent_menu_item = $menu_item_class::create([
            'menu_id' => $menu->id,
            'name' => 'Parent ' . $menu->position . ' Menu Item',
            'children' => $child_menu_items,
        ]);

        return 0;
    }
}
