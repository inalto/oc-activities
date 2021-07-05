<?php namespace MartiniMultimedia\Activities\Components;

use Redirect;
use BackendAuth;
use Cms\Classes\ComponentBase;
use MartiniMultimedia\Activities\Models\Activity as A;

/**
 * Activity Page Component
 */
class ActivityPage extends ComponentBase
{
    public $activity;

    public function componentDetails()
    {
        return [
            'name'        => 'martinimultimedia.activities::lang.components.page.name',
            'description' => 'martinimultimedia.activities::lang.components.page.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'martinimultimedia.activities::lang.componens.page.slug',
                'description' => 'martinimultimedia.activities::lang.componens.page.slug_description',
                'default'     => '{{ :slug }}',
                'type'        => 'string'
            ]
        ];
    }

    public function onRun()
    {
        $this->addCss("assets/leaflet/leaflet.css");
        $this->addJs("assets/leaflet/leaflet.js");
        $this->addCss("assets/leaflet-routing-machine/leaflet-routing-machine.css");
        $this->addJs("assets/leaflet-routing-machine/leaflet-routing-machine.js");
        $this->addCss("assets/leaflet-control-geocoder/Control.Geocoder.css");
        $this->addJs("assets/leaflet-control-geocoder/Control.Geocoder.min.js");


        $activity = $this->loadEvent();
        if (!$activity || !$activity->exists) {
            $this->setStatusCode(404);
            return $this->controller->run('404');
           }
    
        $this->activity = $this->page['activity'] = $activity;
        $this->page['title'] = $activity->title;
    }

    protected function loadEvent()
    {
        $slug = $this->property('slug');
        $activity = new A;
        $activity = $activity->isClassExtendedWith('RainLab.Translate.Behaviors.TranslatableModel')
            ? $activity->transWhere('slug', $slug)
            : $activity->where('slug', $slug);
        if (!$this->checkEditor()) {
            $activity = $activity->isPublished();
        }
        
        return $activity;
    }

    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();
        return $backendUser && $backendUser->hasAccess('martinimultimedia.activities.access_activities');
    }


}
