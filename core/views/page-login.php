<?
get_header();
the_post();
?>

<div class="post" style="background-size: 100%;">
    <div class="postcenter" style="padding: 5px 20px; margin: 0px;">
        <div class="posttitle"><?echo the_title();?></div>
        <div class="postcontent"><?wp_login_form();?></div>
    </div>
</div>

<?get_footer();?>
