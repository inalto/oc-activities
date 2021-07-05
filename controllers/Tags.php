<?php namespace MartiniMultimedia\Activities\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Tags extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public $requiredPermissions = [
        'martinimultimedia.activities.access_tags',
    ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('martinimultimedia.activities', 'activities-item', 'side-activity-tag');
    }
}
