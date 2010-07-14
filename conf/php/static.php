<?php
/*
 * Static files configuration
 * The proxy between tool and web pages
 */
$static = array();

$static["raw"][] = array(
    "type"        => "css",
    "href"        => STATIC_URL . "yui/3.1.1/cssreset/reset.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "yui/3.1.1/cssfonts/fonts.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "yui/3.1.1/cssgrids/grids.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "core.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "mod.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "_masthead.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "_photo_list.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "_photo_filter.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "_photo_viewer.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "css", 
    "href"        => STATIC_URL . "_introduction.css", 
    "is_top"      => TRUE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/yui/yui.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/oop/oop.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/dom/dom.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/event-custom/event-custom.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/event/event.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/pluginhost/pluginhost.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/node/node.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/classnamemanager/classnamemanager.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/attribute/attribute.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/base/base.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/plugin/plugin.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/event-simulate/event-simulate.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/node/node-event-simulate.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/node-focusmanager/node-focusmanager.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "yui/3.1.1/substitute/substitute.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "core.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "_photo_viewer.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "_photo_filter.js", 
    "is_top"      => FALSE, 
);
$static["raw"][] = array(
    "type"        => "js", 
    "src"         => STATIC_URL . "_photo_list.js", 
    "is_top"      => FALSE, 
);

$static["index"][] = array(
    "type"        => "css",
    "href"        => STATIC_URL . "mini?module=index&type=css",
    "is_top"      => TRUE,
);
$static["index"][] = array(
    "type"        => "js",
    "src"         => STATIC_URL . "mini?module=index&type=js",
    "is_top"      => TRUE,
);
?>
