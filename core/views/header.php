<?header("Content-Type: text/html, charset=windows-1251");?>
<!DOCTYPE html>
<html>
    <head>
        <title><?if(!empty($title)) echo "{$title} &mdash; ";?>Gazville.Ru</title>

        <meta charset="windows-1251">
        <meta name="viewport" content="width=device-width">

        <link rel="icon" href="/assets/images/logo-150x150.jpg" sizes="32x32" />
        <link rel="icon" href="/assets/images/logo.jpg" sizes="192x192" />

        <link rel="stylesheet" href="/assets/styles/style.css" />
        <link rel="stylesheet" href="/assets/styles/loading.css" />
        <link rel="stylesheet" href="/assets/styles/sidebar.css" />
        <link rel="stylesheet" href="/assets/styles/header.css" />

        <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/js/script.js"></script>
    </head>
    <body onLoad="$('#loadinglayer').css('display', '');" onBeforeUnload="$('#loadinglayer').css('display', 'block');">
        <div id="loadinglayer" style="display: block;">
            ��������
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
        <?include(View::$path['view'] . "sidebar.php");?>
        <div id="center">
            <div id="header">
                <a class="vk" href="https://vk.com/club70404044" target="_blank">������</a><!--
             --><a class="info" href="/info">����������</a><!--
             --><a class="events" href="/events">�������</a><!--
             --><a class="rubrics" href="/c/rubrics">�������</a><!--
             --><a class="feedback" href="/feedback">�������� �����</a>
            </div>
            <div id="content">
