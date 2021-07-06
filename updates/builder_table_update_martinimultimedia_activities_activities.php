<?php namespace MartiniMultimedia\Activities\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaActivitiesActivities extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_activities_activities', function($table)
        {
            $table->text('points')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_activities_activities', function($table)
        {
            $table->dropColumn('points');
        });
    }
}
