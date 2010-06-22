<?php
define("MINI_CONFIG", isset($_GET["conf"]) ? $_GET["conf"] : getenv("DEV_ROOT") . "/conf/mini/mini.xml");

set_exception_handler("handleException");

require_once "./Mini.php";

if (!isset($_GET["module"])) 
{
    throw new MiniException("No module specified");
}

if (!isset($_GET["type"])) 
{
    throw new MiniException("No type specified");
}

$modules = array();
$mini    = new Mini(MINI_CONFIG);
$type    = $_GET["type"];

if ($type !== "css" && $type !== "js") 
{
    throw new MiniException("Invalid type: $type");
}

if ($_GET["module"] === "all") 
{
    $modules = $mini->get_modules();
} 
else 
{
    $modules[] = $mini->get_module($_GET["module"]);
}
header("Content-Type: " . ($type === "css" ? "text/css" : "text/javascript"));

foreach ($modules as $module) {
    if ($_GET["type"] === "css") 
    {
        $module->echo_css(!isset($_GET["nominify"]));
    } 
    else if ($_GET["type"] === "js") 
    {
        $module->echo_js(!isset($_GET["nominify"]));
    }
}

function handleException(Exception $ex) {
    header('HTTP/1.0 404 Not Found');
    header('Content-type: text/html');
    echo '<html>';
    echo '<head><title>Mini error</title></head>';
    echo '<body>';
    echo '<h1>Mini error</h1>';
    echo '<p>' . htmlentities($ex->getMessage()) . '</p>';
    echo '</body>';
    echo '</html>';
    exit;
}
?>
