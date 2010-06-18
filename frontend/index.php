<?php
define("CONFIG_PATH", "/var/www/josephj/lab/modev/conf/");
require_once CONFIG_PATH  . "constant.php";
require_once LIBRARY_PATH . "Function.php";
require_once LIBRARY_PATH . "StaticFile.php";

$static = new StaticFile(CONFIG_PATH . "static.php", "index");
$static_html["top"]    = $static->getTopFiles();
$static_html["bottom"] = $static->getBottomFiles();
require_once VIEW_PATH . "index.php";

?>
