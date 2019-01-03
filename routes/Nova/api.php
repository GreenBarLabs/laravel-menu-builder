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
Route::get('/list-menu-items/{menu_id}', MenuBuilderController::class . '@list_menu_items');
Route::post('/save-menu-order/{menu_id}', MenuBuilderController::class . '@save_menu_order');

Route::get('/get-menu-item/{menu_item_id}', MenuBuilderController::class . '@get_menu_item');
Route::post('/create-menu-item/{menu_id}', MenuBuilderController::class . '@create_menu_item');
Route::post('/update-menu-item/{menu_item_id}',  MenuBuilderController::class . '@update_menu_item');
Route::post('/delete-menu-item/{menu_item_id}',  MenuBuilderController::class . '@delete_menu_item');
