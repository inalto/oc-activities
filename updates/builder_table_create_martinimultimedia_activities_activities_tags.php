<?php namespace MartiniMultimedia\Activities\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateMartiniMultimediaActivitiesActivitiesTags extends Migration
{
    public function up()
    {
        Schema::dropIfExists('martinimultimedia_activities_activities_tags');
        Schema::create('martinimultimedia_activities_activities_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->bigInteger('activity_id');
            $table->bigInteger('tag_id');
            $table->primary(['activity_id','tag_id'],'activities_tags');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('martinimultimedia_activities_activities_tags');
    }
}
