<?php
    $this->setData("title", "Обратная cвязь: Все обращения");
    $this->placeView("header.php");
?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle">Обратная связь: Все обращения</div>
        <div class="postcontent"></div>
    </div>
</div>

<?php
    foreach($messages as $message) {
        extract($message);
?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle"><?=$msg_subject?></div>
        <a href="/feedback/<?=$msg_id?>"><?=$msg_time?></a>
        <div class="postcontent"><?=$msg_content?></div>
    </div>
</div>

<?php
    }

    $this->placeView("footer.php");
?>