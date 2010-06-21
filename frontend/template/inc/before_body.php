<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?php echo $data["title"], " | ", $data["site_name"]; ?></title>
<?php echo (isset($static_html["top"]) ? $static_html["top"] : ""); ; ?>
</head>
<body class="yui-skin-sam">
    <h1><?php echo $data["title"]; ?></h1>
