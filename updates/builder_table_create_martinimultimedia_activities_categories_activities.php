<?php namespace MartiniMultimedia\Activities\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartiniMultimediaEventsCategoriesActivities extends Migration {

    public function up()
    {
        Schema::dropIfExists('martinimultimedia_activities_categories_activities');
        Schema::create('martinimultimedia_activities_categories_activities', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('category_id');
            $table->bigInteger('activity_id');
            $table->primary(['category_id','activity_id'],'categories_activities');
        });

    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_activities_categories_activities');
    }
}
