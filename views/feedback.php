<?$this->setData("title", "Обратная cвязь");
$this->placeView("header.php");?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle">Обратная связь</div>
        <div class="postcontent">
            <form method="POST" action="/feedback" id="feedbackform">
                <label for="themefiled">Тема: <input type="text" name="theme" id="themefiled" required></label>
                <?if(!User::isUserLoggedIn()):?>
                	<label for="emailfield">Email: <input type="email" name="email" id="emailfield" required></label>
                <?endif;?>
 				<label for="messagefield">Сообщение: <textarea name="message" id="messagefield" required></textarea></label>
                <input type="submit" value="Отправить">
            </form>
        </div>
    </div>
</div>

<?$this->placeView("footer.php");?>