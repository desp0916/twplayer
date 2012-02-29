<!DOCTYPE html>
<html>
<head>
  <title><?php echo $page_title?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel="apple-touch-icon" href="http://<?php echo $static_host ?>/pub/img/apple-touch-icon.png" />
  <link rel="stylesheet" type="text/css" href="http://<?php echo $static_host ?>/pub/css/default.css" media="screen" />
  <script type="text/javascript" src="http://<?php echo $static_host ?>/pub/js/jquery-1.7.1.min.js"></script>
</head>
<body>
<?php include($_navbar); ?>
  <div id="main_body">
<?php include($_menu); ?>
