<?$this->placeView("header.php");?>

<div class="post" style="background-size: 100%;">
    <div class="postcenter" style="padding: 5px 20px; margin: 0px;">
        <div class="posttitle">Вход</div>
        <div class="postcontent">
            <?if(isset($error)):?><?=$error?><?endif;?>
            <form method="POST" action="/login" id="loginform">
                <label for="loginfield">Логин: <input type="text" name="login" id="loginfield" required></label>
                <label for="passwordfield">Пароль: <input type="password" name="password" id="passwordfield" required></label>
                <label for="remember" style="cursor: pointer;">Запомнить пароль <span class="checkbox"><input type="checkbox" name="remember" id="remember"><span></span></label>
                <input type="submit" value="Войти">
            </form>
        </div>
    </div>
</div>

<?$this->placeView("footer.php");?>
