<?php namespace MartiniMultimedia\Activities\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateMartinimultimediaActivitiesActivities2 extends Migration
{
    public function up()
    {
        Schema::table('martinimultimedia_activities_activities', function($table)
        {
            $table->text('features')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('martinimultimedia_activities_activities', function($table)
        {
            $table->dropColumn('features');
        });
    }
}
