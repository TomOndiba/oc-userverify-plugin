<?php namespace Uxms\Userverify\Updates;

use Db;
use October\Rain\Database\Updates\Migration;

class MakeUserverifyDateverifiedNull extends Migration {

    public function up()
    {
        Db::statement('ALTER TABLE `users` MODIFY `userverify_dateverified` TIMESTAMP NULL;');
    }

    public function down()
    {
    }

}
