<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('menu_id')->unsigned()->index();
            $table->bigInteger('parent_id')->unsigned()->nullable()->default(null);
            $table->bigInteger('lft')->unsigned()->index();
            $table->bigInteger('rgt')->unsigned()->index();
            //$table->bigInteger('file_id');
            $table->bigInteger('model_id')->unsigned()->nullable()->default(null);
            $table->string('model_type')->nullable()->default(null);
            $table->boolean('is_divider')->default(0);
            $table->string('name');
            $table->string('title')->nullable()->default(null);
            $table->string('icon')->nullable()->default(null);
            $table->string('target')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
            $table->longText('url')->nullable()->default(null);
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
