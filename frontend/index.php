<?php
define("CONFIG_PATH", "/var/www/josephj/lab/modev/conf/php/");

// model
require_once CONFIG_PATH  . "constant.php";
require_once LIBRARY_PATH . "Function.php";
require_once LIBRARY_PATH . "StaticFile.php";

//  controller
$data = array();
$data["title"] = "webrebuild.org 第四届年会";
$data["site_name"] = "josephj.com";

$static = new StaticFile(CONFIG_PATH . "static.php", "index");
$static_html["top"]    = $static->get_top_files();
$static_html["bottom"] = $static->get_bottom_files();

// view 
require_once VIEW_PATH . "index.php";

?>
