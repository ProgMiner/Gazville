<?$inTheLoop = in_the_loop();?>
<div class="post"<?if(!$inTheLoop){?> style="background-size: 100%;"<?}?>>
    <?if($inTheLoop):?>
        <div class="postsidebar">
            <a href="/profile/<?the_author_meta("ID");?>" style="cursor: default;"><img src="<?echo gazville_avatar_url(get_the_author_meta("user_email"));?>" class="postavatar"></a>
            <a href="/profile/<?the_author_meta("ID");?>" class="postauthor"><?the_author();?></a>
        </div>
    <?endif?>
    <div class="postcenter"<?if(!$inTheLoop){?> style="padding: 5px 20px; margin: 0px;"<?}?>>
        <?if($inTheLoop):?>
            <div class="posttitle"><a href="<?echo the_permalink();?>"><?echo the_title();?></a></div>
            <a href="<?echo the_permalink();?>"><?echo the_time("d-m-Y, H:i");?></a>
        <?endif;?>
        <div class="postcontent"><?if($inTheLoop) the_content(); else echo "Записей нет";?></div>
    </div>
</div>
