<?php namespace MartiniMultimedia\Activities\Models;

use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\NestedTree;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'martinimultimedia_activities_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsToMany = [
        'activities' => ['MartiniMultimedia\Activities\Models\Activity',
            'table' => 'martinimultimedia_activities_categories_activities',
            'key'      => 'activity_id',
            'otherKey' => 'category_id',
            'order' => 'published_at desc',
//            'scope' => 'isPublished'
        ]
    ];
    
}
