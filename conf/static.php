<?php
/*
 * Static files configuration
 * The proxy between tool and web pages
 */

$static = array();
$static["index"][] = array(
    "tag"         => "script",
    "type"        => "text/javascript", 
    "src"         => STATIC_URL . "min?module=core&type=js", 
    "is_top"      => FALSE, 
);
/* 
    output : 
    <body>
    ...
    <script type="text/javascript" src="http://aaa.com/min?module=core&type=js"></script>
    </body>
*/
$static["index"][] = array(
    "tag"         => "link"
    "type"        => "text/css",  // alias : css, js
    "rel"         => "stylesheet",
    "href"        => STATIC_URL . "min?module=core&type=js&nominify&nocombo", 
    "media"       => "screen",
    "is_top"      => TRUE, 
);
/* 
    output : 
    <link type="text/css" rel="stylesheet" href="http://aaa.com/min?module=core&type=css" media="screen">
    <body>
    ...
    </body>
*/
$static["index"][] = array(
    "tag"         => "link"
    "type"        => "text/css", 
    "rel"         => "stylesheet",
    "href"        => STATIC_URL . "min?module=core_nojs&type=css", 
    "media"       => "screen",
    "is_top"      => TRUE, 
    "is_noscript" => TRUE,
);
/* 
    output : 
    <noscript>
    <link type="text/css" rel="stylesheet" href="http://aaa.com/min?module=core_nojs&type=css">
    </noscript>
    <body>
    ...
    </body>
*/
?>
