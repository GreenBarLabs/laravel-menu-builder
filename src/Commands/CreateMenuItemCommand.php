<?php
namespace GreenBar\MenuBuilder\Commands;

use Illuminate\Console\Command;

class CreateMenuItemCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menus:create-menu-item';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a menu item with GreenBar\\MenuBuilder';

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
        // TODO: 2018-11-13: Create the menu item relate the menu item to the specified menu,
        //                   and the re-index all of the left_id and right_id columns on the
        //                   menu's menu items.
        return 0;
    }
}
