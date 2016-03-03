<?php namespace Uxms\Userverify\Updates;

use Illuminate\Support\Facades\Schema;
use October\Rain\Database\Updates\Migration;

class AddUsersColumns extends Migration {

	public function up()
    {
		Schema::table('users', function($table) {
			$table->timestamp('userverify_dateverified');
			$table->string('userverify_callerphone')->nullable();
        });
	}

	public function down()
    {
        /*
		if (Schema::hasColumn('users', 'userverify_dateverified')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('userverify_dateverified');
            });
        }
		if (Schema::hasColumn('users', 'userverify_callerphone')) {
            Schema::table('users', function($table)
            {
                $table->dropColumn('userverify_callerphone');
            });
        }
        */
	}

}
