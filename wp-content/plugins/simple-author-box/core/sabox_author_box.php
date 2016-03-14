<?php

// If this file is called directly, busted!
if( !defined( 'ABSPATH' ) ) {
    exit;
}

/*----------------------------------------------------------------------------------------------------------
    Adding the author box to the end of your single post
-----------------------------------------------------------------------------------------------------------*/

if( !function_exists( 'wpsabox_author_box' ) ) {


    function wpsabox_author_box( $saboxmeta = null ) {

        if ( is_single() or is_author() or is_archive() ) {

            global $post;
            global $options;

            $author_id = $post->post_author;

            if( isset( $options['sab_colored'] ) ) {
                $sabox_color = 'sabox-colored';
            } else {
                $sabox_color    = '';
            }

            if( isset( $options['sab_web_position'] ) ) {
                $sab_web_align = 'sab-web-position';
            } else {
                $sab_web_align = '';
            }

            if( isset( $options['sab_web_target'] ) ) {
                $sab_web_target = '_blank';
            } else {
                $sab_web_target = '_self';
            }

             if( isset( $options['sab_web_rel'] ) ) {
                $sab_web_rel = 'rel="nofollow"';
            } else {
                $sab_web_rel = '';
            }


            if( get_the_author_meta( 'description' ) != '' || !isset( $options['sab_no_description'] ) ) { // hide the author box if no description is provided

            $saboxmeta .= '<div class="saboxplugin-wrap">'; // start saboxplugin-wrap div

            // author box gravatar
            $saboxmeta .= '<div class="saboxplugin-gravatar">';
            $saboxmeta .= get_avatar( get_the_author_meta( 'user_email', $author_id ), '100' );
            $saboxmeta .= '</div>';

            // author box name
            $saboxmeta .= '<div class="saboxplugin-authorname">';
            $saboxmeta .= '<a href="' . get_author_posts_url( $author_id ) . '">' . get_the_author_meta( 'display_name', $author_id ) . '</a>';
            $saboxmeta .= '</div>';


            // author box description
            $saboxmeta .= '<div class="saboxplugin-desc">';
            $saboxmeta .= '<div class="vcard author"><span class="fn">';
            $saboxmeta .=  get_the_author_meta( 'description', $author_id );
            $saboxmeta .= '</span></div>';
            $saboxmeta .= '</div>';

            if ( is_single() ) {
            if( get_the_author_meta( 'user_url' ) != '' and isset( $options['sab_web'] ) ) { // author website on single
            $saboxmeta .= '<div class="saboxplugin-web '. $sab_web_align .'">';
            $saboxmeta .= '<a href="' . get_the_author_meta( 'user_url', $author_id ) . '" target="' . $sab_web_target . '" ' . $sab_web_rel . '>' . get_the_author_meta( 'user_url', $author_id ) . '</a>';
            $saboxmeta .= '</div>';
            }
            }


            if ( is_author() or is_archive() ) {
            if( get_the_author_meta( 'user_url' ) != '' ) { // force show author website on author.php or archive.php
            $saboxmeta .= '<div class="saboxplugin-web '. $sab_web_align .'">';
            $saboxmeta .= '<a href="' . get_the_author_meta( 'user_url', $author_id ) . '" target="' . $sab_web_target . '" ' . $sab_web_rel . '>' . get_the_author_meta( 'user_url', $author_id ) . '</a>';
            $saboxmeta .= '</div>';
            }
            }



            // author box clearfix
            $saboxmeta .= '<div class="clearfix"></div>';

            // author box social icons

             if( !isset( $options['sab_hide_socials'] ) ) { // hide social icons div option
                $saboxmeta .= '<div class="saboxplugin-socials ' . $sabox_color . '">';

                foreach ( Sabox_Social_Icons::$sabox_social_icons_array as $sabox_social_id => $sabox_social_name ) {

                    $sabox_author_fields = get_the_author_meta( $sabox_social_id );

                    if ( !empty( $sabox_author_fields ) ) {
                        $saboxmeta .= Sabox_Social_Icons::get_sabox_social_icon( $sabox_author_fields, $sabox_social_id );
                    }
                }

                $saboxmeta .= '</div>';
            } // end of social icons
            $saboxmeta .= '</div>'; // end of saboxplugin-wrap div

        }
    }
    return $saboxmeta;
}

}


function saboxplugin_position() {

    global $options;

    if( !isset( $options['sab_autoinsert'] ) ) {
        add_filter ( 'the_content', 'wpsabox_author_box', 0 );
    }

}

echo saboxplugin_position();