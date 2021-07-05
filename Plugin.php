<?php namespace MartiniMultimedia\Activities;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'MartiniMultimedia\Activities\Components\ActivitiesList' => 'activitiesList',
            'MartiniMultimedia\Activities\Components\ActivityPage' => 'activityPage'
    ];
    }

    public function registerSettings()
    {
    }
}
