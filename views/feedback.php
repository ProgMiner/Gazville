<?
    $this->setData("title", "Обратная cвязь");
    $this->placeView("header.php");
?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle">Обратная связь</div>
        <div class="postcontent">
            <form method="POST" action="/feedback" id="feedbackform">
                <label for="themefield">Тема: <input type="text" name="theme" id="themefield" required></label>
                <?if(!User::isUserLoggedIn()):?>
                	<label for="emailfield">E-Mail: <input type="email" name="email" id="emailfield" required></label>
                <?endif;?>
 				<label for="messagefield" class="tarea">Сообщение: <textarea name="message" id="messagefield" style="width: 400px; height: 200px; resize: none;" required></textarea></label>
                <input type="submit" value="Отправить">
            </form>
        </div>
    </div>
</div>

<?$this->placeView("footer.php");?>