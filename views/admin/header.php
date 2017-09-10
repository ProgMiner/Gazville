<?php header("Content-Type: text/html; charset=" . View::$charset);?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php if(!empty($title)) echo "{$title} &mdash; ";?>Админпанель Gazville.Ru</title>

        <meta charset="<?=View::$charset?>">
        <meta name="viewport" content="width=device-width">

        <link rel="icon" href="/assets/images/logo-150x150.jpg" sizes="32x32" />
        <link rel="icon" href="/assets/images/logo.jpg" sizes="192x192" />

        <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
    </head>
    <body>