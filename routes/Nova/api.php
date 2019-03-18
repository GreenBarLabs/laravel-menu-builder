<?php

use Illuminate\Support\Facades\Route;
use GreenBar\MenuBuilder\Nova\Http\Controllers\MenuBuilderController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::get('/dashboard', MenuBuilderController::class . '@index');

// Menu Item Operations
Route::get('/menus/{menu_id}/menu-items', MenuBuilderController::class . '@list_menu_items');
Route::post('/menus/{menu_id}/save-menu-order', MenuBuilderController::class . '@save_menu_order');

Route::post('/menus/{menu_id}/menu-items', MenuBuilderController::class . '@store_menu_item');
Route::get('/menus/{menu_id}/menu-items/{menu_item_id}', MenuBuilderController::class . '@get_menu_item');
Route::put('/menus/{menu_id}/menu-items/{menu_item_id}', MenuBuilderController::class . '@update_menu_item');
Route::delete('/menus/{menu_id}/menu-items/{menu_item_id}', MenuBuilderController::class . '@delete_menu_item');
