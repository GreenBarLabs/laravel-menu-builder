<?php

namespace GreenBar\MenuBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;

class MenuItem extends Model
{
    use SoftDeletes;
    use NodeTrait; //https://github.com/lazychaser/laravel-nestedset

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
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

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
    protected function getScopeAttributes()
    {
        return [ 'menu_id' ];
    }
    
    public function getLftName()
    {
        return 'lft';
    }

    public function getRgtName()
    {
        return 'rgt';
    }
    
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
