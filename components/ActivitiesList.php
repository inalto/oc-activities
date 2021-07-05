<?php namespace MartiniMultimedia\Activities\Components;

use Redirect;
use BackendAuth;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use MartiniMultimedia\Activities\Models\Activity as A;
use Input;
use Log;

/**
 * ActivitiesList Component
 */

class ActivitiesList extends ComponentBase
{

    public $activities;

    /**
    * Reference to the page name for linking to posts.
    * @var string
    */
   public $activityPage;

   public $pageParam;
   public $pageNumber;
   public $paginate;

   public $categories;

   public $no_activities_text;

    /**
    * If the post list should be ordered by another attribute.
    * @var string
    */
   public $sortOrder;


    public function componentDetails()
    {
        return [
            'name'        => 'martinimultimedia.activities::lang.components.list.name',
            'description' => 'martinimultimedia.activities::lang.components.list.description'
        ];
    }

    public function defineProperties()
    {
        return [
            'pageNumber' => [
                'title'       => 'martinimultimedia.activities::lang.components.list.page_number',
                'description' => 'martinimultimedia.activities::lang.components.list.page_number_description',
                'type'        => 'string',
                'default'     => '{{ :page }}',
            ],
            'activitiesPerPage' => [
                'title'             => 'martinimultimedia.activities::lang.components.list.activities_per_page',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'martinimultimedia.activities::lang.components.list.activities_per_page_validation',
                'default'           => '10',
            ],
            'skip' => [
                'title'             => 'martinimultimedia.activities::lang.components.list.skip',
                'description' => 'martinimultimedia.activities::lang.components.list.skip_description',
                'type'              => 'string',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'martinimultimedia.activities::lang.components.list.skip_validation',
                'default'           => '0',
            ],
            'paginate' => [
                'title'             => 'martinimultimedia.activities::lang.components.list.paginate',
                'description'       => 'martinimultimedia.activities::lang.components.list.paginate_description',
                'type'              => 'dropdown',
                'default'           => '1',
            ],
            'sortOrder' => [
                'title'       => 'martinimultimedia.activities::lang.components.list.activities_order',
                'description' => 'martinimultimedia.activities::lang.components.list.activities_order_description',
                'type'        => 'dropdown',
                'default'     => 'date_from asc'
            ],
            'activityPage' => [
                'title'       => 'martinimultimedia.activities::lang.components.list.event_page',
                'description' => 'martinimultimedia.activities::lang.components.list.event_page_description',
                'type'        => 'dropdown',
                'default'     => 'event/post',
                'group'       => 'Links',
            ],
            'categories' => [
                'title'       => 'martinimultimedia.activities::lang.components.list.categories',
                'description' => 'martinimultimedia.activities::lang.components.list.categories_description',
                'type'        => 'string',
                'default'     => '{{ :categories }}',
            ],
        ];
    }

    public function getactivityPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Options array for the pagination dropdown.
     *
     * @return array
     */
    public function getPaginateOptions()
    {
        return [
                0 => trans('martinimultimedia.activities::lang.components.list.paginator_none'),
                1 => trans('martinimultimedia.activities::lang.components.list.paginator_full'),
                2 => trans('martinimultimedia.activities::lang.components.list.paginator_incremental'),
            ];
    }



    public function getSortOrderOptions()
    {
        return A::$allowedSortingOptions;
    }


    public function onRender()
    {        
        $this->prepareVars();
        
        if ($this->paginate) {
        /*
         * If the page number is not valid, redirect
         */
        if ($pageNumberParam = $this->paramName('pageNumber')) {
            $currentPage = $this->property('pageNumber');
            if ($currentPage > ($lastPage = $this->activities->lastPage()) && $currentPage > 1)
                return Redirect::to($this->currentPageUrl([$pageNumberParam => $lastPage]));
            }
        }
    }
    /**
     * Paginate the result set.
     *
     * @param Collection $items
     * @param int        $totalCount
     *
     * @return LengthAwarePaginator
     */
    protected function onLoadMore()
    {
        try {
            

            $this->prepareVars();
        } catch (ModelNotFoundException $e) {
            return $this->controller->run('404');
        }

        if ($this->pageNumber >= ($lastPage = $this->activities->lastPage()) && $this->pageNumber > 1)
        return [
            '#loadmore' => '',
            '@#activities' => $this->renderPartial('@items.htm'),
        ];

        return [
            '#loadmore' => $this->renderPartial('@loadmore.htm'),
            '@#activities' => $this->renderPartial('@items.htm'),
        ];
    }




    protected function prepareVars()
    {

        
        $this->pageParam = $this->page['pageParam'] = $this->paramName('pageNumber');
        
        if (Input::get('pageNumber')) {
        $this->pageNumber = $this->page['pageNumber'] =Input::get('pageNumber');
        } else {
        $this->pageNumber = $this->page['pageNumber'] =  $this->property('pageNumber')?$this->property('pageNumber'):1;
        }
        $this->paginate = $this->page['paginate'] = $this->property('paginate');
        
        $this->categories = $this->page['categories']=$this->property('categories');
        $this->no_event_text = $this->page['no_activities_text'] = trans('martinimultimedia.activities::lang.components.list.no_activities');
        $this->activityPage = $this->page['activityPage'] = $this->property('activityPage');
        $this->activities = $this->page['activities'] = $this->listActivities();

        //Log::info($this->paginate." ".$this->paramName('paginate')."-".$this->property('paginate') );
        /*
         * Page links
         */

    }

    protected function listActivities()
    {
        //$lifestyle = $this->lifestyle ? $this->lifestyle->id : null;
        /*
         * List all the posts, eager load their lifestyles
         */
        $isPublished = !$this->checkEditor();
        $activities = A::listFrontEnd([
            'page'       => $this->pageNumber,
            'sort'       => $this->property('sortOrder'),
            'perPage'    => $this->property('activitiesPerPage'),
            'skip'       => $this->property('skip'),
            'paginate'   => $this->property('paginate'),
            'categories'    => array_map('trim',explode(",",$this->property('categories'))),
            'search'     => trim(input('search'))
        ]);
        /*
         * Add a "url" helper attribute for linking to each post and lifestyle
         */
        $activities->each(function($a) {
            $a->setUrl($this->activityPage, $this->controller);
        });


        return $activities;
    }
    
    protected function checkEditor()
    {
        $backendUser = BackendAuth::getUser();
        return $backendUser && $backendUser->hasAccess('martinimultimedia.press.access_activities');
    }

}
