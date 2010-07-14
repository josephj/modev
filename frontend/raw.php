<?php
define("CONFIG_PATH", "/var/www/josephj/lab/modev/conf/php/");

// Load model files
require_once CONFIG_PATH  . "constant.php";
require_once LIBRARY_PATH . "Function.php";
require_once LIBRARY_PATH . "StaticFile.php";

//  controller
$data = array();
$data["title"] = "Modular Development (Raw) @ webrebuild.org";
$data["site_name"] = "josephj.com";

$static = new StaticFile(CONFIG_PATH . "static.php", "raw");
$static_html["top"]    = $static->get_top_files();
$static_html["bottom"] = $static->get_bottom_files();

$feed = getPhoto();
$feed = $feed["items"];
$photos = array();
foreach ($feed as $item)
{
    if (preg_match("/(http:\/\/.+static.flickr.com\/\d+\/\d+_[0-9a-z]+)_m\.jpg/", $item["media"]["m"], $result))
    {
        $cache = array();
        $cache["src"] = "{$result[1]}_s.jpg";
        $cache["link"] = $item["link"];
        $cache["title"] = $item["title"];
        $photos[] = $cache;
        unset($cache);
    }
}
// view 
require_once VIEW_PATH . "index.php";

?>
