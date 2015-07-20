<?php

function anp_global_menu_errors() {

    global $anp_global_menu_errors;

    echo '<pre>';
    print_r($anp_global_menu_errors);
    echo '</pre>';

    global $blog_id;

    if( is_main_site() ) {
        echo '<pre>';
        echo 'Is Main Site: ';
    } else {
        echo '<pre>';
        echo 'NOT Main Site: ';
    }

    echo get_bloginfo( 'url' );
    echo 'Blog ID: ' . $blog_id;
    echo '</pre>';

}

add_action('wp_footer', 'anp_global_menu_errors', 99999, 99); 

?>