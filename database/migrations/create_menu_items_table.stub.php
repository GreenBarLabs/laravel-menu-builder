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
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('CASCADE');

            $table->bigInteger('parent_id')->unsigned()->index()->nullable()->default(null);
            $table->bigInteger('lft')->unsigned()->index()->nullable()->default(null);
            $table->bigInteger('rgt')->unsigned()->index()->nullable()->default(null);

            //$table->bigInteger('file_id')->unsigned()->index()->nullable()->default(null);
            //$table->foreign('file_id')->references('id')->on('files')->onDelete('SET NULL');

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
