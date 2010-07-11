<?php
$menu_items = array(
    "ref" => array(
        "url"   => "/cms",   
        "text"  => "References", 
        "items" => array(
            "sds" => array(
                "url"  => "/cms/sds/index", 
                "text" => "SpaceID"
        ),
    ))
);
include_once VIEW_PATH . "_navigation.php";
?>
