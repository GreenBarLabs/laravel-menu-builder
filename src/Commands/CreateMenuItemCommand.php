<?php
namespace Greenbar\MenuBuilder\Commands;

use Illuminate\Console\Command;

class CreateMenuItemCommand extends Command
{
    /**
     * The environment this command is running in
     *
     * @var boolean
     */
    private $app_env = 'production';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu-builder:create-menu-item';
    
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

        // TODO: DISCUSS 2018-11-13: Do this???
        $this->app_env = strtolower(env('APP_ENV', 'production'));

        // TODO: 2018-11-23: Change this to check config values instead of env values
        $this->debug_messaging = ($this->app_env !== 'production') ? true : false;
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
