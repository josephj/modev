<?php
/*
 * Static files configuration
 * The proxy between tool and web pages
 */

$static = array();
$static["index"][] = array(
    "type"        => "css",
    "href"        => STATIC_URL . "yui/2.8.1/reset/reset-min.css", 
    "is_top"      => TRUE, 
);
$static["index"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "yui/2.8.1/fonts/fonts-min.css", 
    "is_top"      => TRUE, 
);
$static["index"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "yui/2.8.1/grids/grids-min.css", 
    "is_top"      => TRUE, 
);
$static["index"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "class.css", 
    "is_top"      => TRUE, 
);
$static["index"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/yui/yui-min.js", 
    "is_top"      => FALSE, 
);
$static["index"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "framework/core.js", 
    "is_top"      => FALSE, 
);
$static["index"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "framework/sandbox.js", 
    "is_top"      => FALSE, 
);
?>
