<?php

namespace Greenbar\MenuBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'menu_id',
    //     'parent_id',
    //     'lft',
    //     'rgt',
    //     'file_id',
    //     'name',
    //     'url',
    //     'title',
    //     'object_id',
    //     'object_type',
    //     'icon',
    //     'is_divider',
    //     'target',
    // ];

    /**
     * The attributes that aren't mass assignable.
     *
     * Everything else will be. Meaning you can use, create() and update() on them.
     * These columns you will have to set manually and then call ->save()
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'lft',
        'rgt',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Attributes
    **/
    
    /**
     * Methods
    **/

    /**
     * Relationships
    **/
    public function menu(): BelongsTo
    {
        return $this->belongsTo(
            config('menu_builder.models.menu'), 
            config('menu_builder.table_names.menu') . '_id'
        );
    }
}
