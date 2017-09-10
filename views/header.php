<?php
    header("Content-Type: text/html; charset=" . View::$charset);
    Route::loadWidget("Sidebar");

    $sidebar = new Widget_Sidebar();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php if(!empty($title)) echo "{$title} &mdash; ";?>Gazville.Ru</title>

        <meta charset="<?=View::$charset?>">
        <meta name="viewport" content="width=device-width">

        <link rel="icon" href="/assets/images/logo-150x150.jpg" sizes="32x32" />
        <link rel="icon" href="/assets/images/logo.jpg" sizes="192x192" />

        <link rel="stylesheet" href="<?=Util::insertLESS("style")?>" />

        <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/js/script.js"></script>
    </head>
    <body onLoad="$('#loadinglayer').css('display', '');" onBeforeUnload="$('#loadinglayer').css('display', 'block');">
        <div id="loadinglayer" style="display: block;">
            Загрузка
            <div id="squaresWaveG">
                <div id="squaresWaveG_1" class="squaresWaveG"></div>
                <div id="squaresWaveG_2" class="squaresWaveG"></div>
                <div id="squaresWaveG_3" class="squaresWaveG"></div>
                <div id="squaresWaveG_4" class="squaresWaveG"></div>
                <div id="squaresWaveG_5" class="squaresWaveG"></div>
                <div id="squaresWaveG_6" class="squaresWaveG"></div>
                <div id="squaresWaveG_7" class="squaresWaveG"></div>
                <div id="squaresWaveG_8" class="squaresWaveG"></div>
            </div>
        </div>
        <?php $sidebar->place();?>
        <div id="center">
            <div id="header"<?php if(!User::isUserLoggedIn()):?> class="slice"<?php endif;?>>
                <a class="vk" href="https://vk.com/club70404044" target="_blank">Группа</a><!--
             --><a class="info" href="/info">Информация</a><!--
             --><a class="events" href="/events">События</a><!--
             --><?php if(User::isUserLoggedIn()):?><!--
                 --><a class="rubrics" href="/c/rubrics">Рубрики</a><!--
             --><?php endif;?><!--
             --><a class="feedback" href="/feedback">Обратная связь</a>
            </div>
            <div id="content">
