<?php
    $this->setData("title", "Обратная cвязь: {$msg_subject}");
    $this->placeView("header.php");
?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle"><?=$msg_subject?></div>
        <a href="/feedback/<?=$msg_id?>"><?=$msg_time?></a>
        <div class="postcontent"><?=$msg_content?></div>
    </div>
</div>

<?php $this->placeView("footer.php");?>