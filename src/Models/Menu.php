<?php

namespace Greenbar\MenuBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'position',
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

    /**
     * A Menu will have numerous menu items.
     */
    public function menu_items(): HasMany
    {
        return $this->hasMany(
            config('menu_builder.models.menu_items'),
            config('menu_builder.table_names.menu') . '_id'
        );
    }
}
