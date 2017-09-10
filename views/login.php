<?php
    $this->setData("title", "Вход");
    $this->placeView("header.php");

    $login = $default['login'];
    if(empty($login) && isset($_COOKIE[User::$cookie_name['session_id']])) {

        $id = $_COOKIE[User::$cookie_name['session_id']];
        $login = Model_User::getLoginById($id);
    }

    if(isset($error))
        switch ($error) {
            case 1:
                $error = "Несуществующий логин!";
                break;
            case 2:
                $error = "Неверный пароль!";
                break;
            default:
                unset($error);
        }
?>

<?php if(isset($error)):?>
<div class="page">
    <div class="postcenter error">
        <div class="postcontent">
            Вход не удался: <b><?=$error?></b>
        </div>
    </div>
</div>
<?php endif;?>

<div class="page">
    <div class="postcenter">
        <div class="posttitle">Вход</div>
        <div class="postcontent">
            <form method="POST" action="/login" id="loginform">
                <label for="loginfield">Логин: <input type="text" name="login" id="loginfield" value="<?=$login?>" required></label>
                <label for="passwordfield">Пароль: <input type="password" name="password" id="passwordfield" value="<?=$default['password']?>" required></label>
                <label for="remember" style="cursor: pointer;">Запомнить пароль <!--
                 --><span class="checkbox"><input type="checkbox" name="remember" id="remember"<?=$default['remember']?>><span></span>
                </label>
                <input type="submit" value="Войти">
            </form>
        </div>
    </div>
</div>

<?php $this->placeView("footer.php");?>
