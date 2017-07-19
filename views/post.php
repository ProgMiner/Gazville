<div class="post">
    <div class="postsidebar">
        <a href="/profile/<?=$author->getData("login")?>" style="cursor: default;"><img src="<?=$author->getData("avatar")?>" class="postavatar"></a>
        <a href="/profile/<?=$author->getData("login")?>" class="postauthor"><?prinft("%s %s", $author->getData("first_name"), $author->getData("last_name"));?></a>
    </div>
    <div class="postcenter">
        <div class="posttitle"><a href="<?=$link?>"><?=$title?></a></div>
        <a href="<?=$link?>"><?=$time?></a>
        <div class="postcontent"><?=$content?></div>
    </div>
</div>
