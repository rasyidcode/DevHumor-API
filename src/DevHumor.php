<?php

define('DEV_HUMOR_ROOT', dirname(__FILE__));

function __autoload(string $class_name) {
    require_once(DEV_HUMOR_ROOT.'/'.$class_name.'.php');
}
require_once(DEV_HUMOR_ROOT."/../vendor/simple_html_dom.php");
require_once(DEV_HUMOR_ROOT.'/DevHumor/DevHumor_v2.php');