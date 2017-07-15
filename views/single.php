<?
get_header();
the_post();
?>

<div class="post">
    <div class="postsidebar">
        <a href="/profile/<?the_author_meta("ID");?>" style="cursor: default;"><img src="<?echo gazville_avatar_url(get_the_author_meta("user_email"));?>" class="postavatar"></a>
        <a href="/profile/<?the_author_meta("ID");?>" class="postauthor"><?the_author();?></a>
    </div>
    <div class="postcenter">
        <div class="posttitle"><?echo the_title();?></div>
        <a href="<?echo the_permalink();?>"><?echo the_time("d-m-Y, H:i");?></a>
        <div class="postcontent"><?the_content();?></div>
    </div>
</div>

<?
wp_link_pages(array(
        'before'	=> "<nav class=\"navigation pagination\" role=\"navigation\">
                                <div class=\"nav-links\">",
        'pagelink'	=> "<span class=\"page-numbers\">%</span>",
        'after'		=> "</div></nav>"
    ));

get_footer();
?>
