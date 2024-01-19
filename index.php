<?php

use Misc\Converter;
use Misc\Db;
use Misc\Utilman;

require 'lib/vendor/autoload.php';

try {
    $db = new Db();
    //$converter = new Converter($db);
    //$converter->convert();
    $utilman = new Utilman($db);
    $utilman->showNonDuplicate();
} catch (Exception $e) {
    echo "An exception has occurred: " . $e->getMessage() . "<br/>";
}



