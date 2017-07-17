<?$this->placeView("header.php");?>

<div class="post" style="background-size: 100%;">
    <div class="postcenter" style="padding: 5px 20px; margin: 0px;">
        <div class="posttitle">Вход</div>
        <div class="postcontent">
        	<form type="post" action="/login.php">
        	<input type="text" name="login" placeholder="Логин" required>
        	<input type="password" name="password" placeholder="Пароль" required>
        	<label for="remember"><input type="checkbox" name="remember" id="remember">Запомнить?</label>
        	<input type="submit" value="Войти">
        	</form>
        </div>
    </div>
</div>

<?$this->placeView("footer.php");?>
