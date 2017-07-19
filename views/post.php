<div class="post">
    <div class="postsidebar">
        <a href="/profile/<?=$author->getData("login")?>" style="cursor: default;"><img src="<?=$author->getData("avatar")?>" class="postavatar"></a>
        <a href="/profile/<?=$author->getData("login")?>" class="postauthor"><?=$author->getData("full_name")?></a>
    </div>
    <div class="postcenter">
        <div class="posttitle"><a href="<?=$link?>"><?=$title?></a></div>
        <a href="<?=$link?>"><?=$time?></a>
        <div class="postcontent"><?=$content?></div>
    </div>
</div>
