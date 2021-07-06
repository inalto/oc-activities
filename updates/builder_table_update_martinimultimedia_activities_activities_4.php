<?php namespace MartiniMultimedia\Activities\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaActivitiesActivities4 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_activities_activities', function($table)
        {
            $table->text('timetable')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_activities_activities', function($table)
        {
            $table->text('timetable')->nullable(false)->change();
        });
    }
}
