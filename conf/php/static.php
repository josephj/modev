<?php
/*
 * Static files configuration
 * The proxy between tool and web pages
 */
$static = array();
/*
$static["index"][] = array(
    "type"        => "css",
    "href"        => STATIC_URL . "yui/3.1.1/cssreset/reset.css", 
    "is_top"      => TRUE, 
);
$static["index"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "yui/3.1.1/cssfonts/fonts.css", 
    "is_top"      => TRUE, 
);
$static["index"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "yui/3.1.1/cssgrids/grids.css", 
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
*/
$static["index"][] = array(
    "type"        => "css",
    "href"        => STATIC_URL . "mini?module=index&type=css",
    "is_top"      => TRUE,
);
$static["index"][] = array(
    "type"        => "js",
    "src"         => STATIC_URL . "mini?module=index&type=js",
    "is_top"      => FALSE,
);
?>
