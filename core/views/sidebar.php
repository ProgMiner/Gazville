<div id="sidebar">
    <a id="sidebarexpand">Свернуть</a>
    <?if(User::isUserLoggedIn()):?>
        <a class="search"><?include(View::$path['view'] . "searchform.php");?></a>
        <a class="split"></a>
        <a href="/profile">Моя страница</a>
        <a class="news" href="/c/feed">Новости</a>
        <a class="messages">Сообщения</a>
        <a class="friends">Друзья</a>
        <a class="groups">Группы</a>
        <a class="photo">Фотографии</a>
        <a class="audio">Аудиозаписи</a>
        <a class="video">Видеозаписи</a>
        <a class="games">Игры</a>
    <?else:?>
        <a class="register">Регистрация</a>
    <?endif;?>
</div>
