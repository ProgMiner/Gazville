<div id="sidebar">
    <a id="sidebarexpand">��������</a>
    <?if(User::isUserLoggedIn()):?>
        <a class="search"><?include(View::$path['view'] . "searchform.php");?></a>
        <a class="split"></a>
        <a href="/profile">��� ��������</a>
        <a class="news" href="/c/feed">�������</a>
        <a class="messages">���������</a>
        <a class="friends">������</a>
        <a class="groups">������</a>
        <a class="photo">����������</a>
        <a class="audio">�����������</a>
        <a class="video">�����������</a>
        <a class="games">����</a>
    <?else:?>
        <a class="register">�����������</a>
    <?endif;?>
</div>
