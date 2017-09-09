<?
    $this->setData("title", "Обратная cвязь");
    $this->placeView("header.php");
?>

<?if(isset($status)):?>
<div class="page">
    <div class="postcenter <?=$status?>">
        <div class="postcontent">
            <?if($status === "error"):?>Произошла ошибка при отправке. Попробуйте ещё раз.
            <?elseif($status === "success"):?>Обращение успешно отправлено<?endif;?>
        </div>
    </div>
</div>
<?endif;?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle">Обратная связь</div>
        <div class="postcontent">
            <form method="POST" action="/feedback" id="feedbackform">
                <label for="subjectfield">Тема: <input type="text" name="subject" id="subjectfield" value="<?=$default['subject']?>" required></label>
                <?if(!User::isUserLoggedIn()):?>
                    <label for="emailfield">E-Mail: <input type="email" name="email" id="emailfield" maxlength="255" value="<?=$default['email']?>" required></label>
                <?endif;?>
                <label for="contentfield" class="tarea">Сообщение: <!--
                 --><textarea name="content" id="contentfield" style="width: 400px; height: 200px; resize: none;" required><?=$default['content']?></textarea><!--
             --></label>
                <input type="submit" value="Отправить">
            </form>
        </div>
    </div>
</div>

<?if(count($last) > 0) {?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle">Последние обращения</div>
        <a href="/feedback/all">Посмотреть все</a>
        <div class="postcontent"></div>
    </div>
</div>

<?
    foreach($last as $message) {
        extract($message);
?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle"><?=$msg_subject?></div>
        <a href="/feedback/<?=$msg_id?>"><?=$msg_time?></a>
        <div class="postcontent"><?=$msg_content?></div>
    </div>
</div>

<?
    }
}

$this->placeView("footer.php");
?>