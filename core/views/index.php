<?get_header();

// циклы вывода записей
// если записи найдены
if(have_posts()){
    while(have_posts()){
        the_post();
        get_template_part("post");
    }
    the_posts_pagination(array(
            'end_size'	=> 0,
            'mid_size'	=> 2,
            'prev_text'	=> "&laquo;",
            'next_text'	=> "&raquo;"
        ));
// если записей не найдено
}else{
    get_template_part("post");
}

get_footer();?>
