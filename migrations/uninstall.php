<?php


use humhub\components\Migration;

class uninstall extends Migration
{

    public function up()
    {
        // We rather remain current space types settings
    }

    public function down()
    {
        echo "uninstall does not support migration down.\n";
        return false;
    }

}
