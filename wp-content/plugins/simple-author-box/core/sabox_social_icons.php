<?php

// If this file is called directly, busted!
if( !defined( 'ABSPATH' ) ) {
    exit;
}

/*----------------------------------------------------------------------------------------------------------
    Social service names
-----------------------------------------------------------------------------------------------------------*/

class Sabox_Social_Icons {

    static $sabox_social_icons_array = array(
        'addthis'       => 'Add This',
        'behance'       => 'Behance',
        'delicious'     => 'Delicious',
        'deviantart'    => 'Deviantart',
        'digg'          => 'Digg',
        'dribbble'      => 'Dribbble',
        'facebook'      => 'Facebook',
        'flickr'        => 'Flickr',
        'github'        => 'Github',
        'google'        => 'Google',
        'googleplus'    => 'Google Plus',
        'html5'         => 'Html5',
        'instagram'     => 'Instagram',
        'linkedin'      => 'Linkedin',
        'pinterest'     => 'Pinterest',
        'reddit'        => 'Reddit',
        'rss'           => 'Rss',
        'sharethis'     => 'Sharethis',
        'skype'         => 'Skype',
        'soundcloud'    => 'Soundcloud',
        'spotify'       => 'Spotify',
        'stackoverflow' => 'Stackoverflow',
        'steam'         => 'Steam',
        'stumbleUpon'   => 'StumbleUpon',
        'tumblr'        => 'Tumblr',
        'twitter'       => 'Twitter',
        'vimeo'         => 'Vimeo',
        'windows'       => 'Windows',
        'wordpress'     => 'Wordpress',
        'yahoo'         => 'Yahoo',
        'youtube'       => 'Youtube',
        'xing'          => 'Xing'
    );

    static function get_sabox_social_icon( $url, $icon_name ) {

        global $options;

        if( isset( $options['sab_link_target'] ) ) {
            $sabox_blank = '_blank';
        } else {
            $sabox_blank = '_self';
        }

        if( isset( $options['sab_colored'] ) ) {
            $sab_color = 'saboxplugin-icon-color';
        } else {
            $sab_color = 'saboxplugin-icon-grey';
        }

        return '<a target="' . $sabox_blank . '" href="' . $url . '"><span class="'. $sab_color . ' saboxplugin-icon-'. $icon_name . '"></span></a>';

    }

}

/*----------------------------------------------------------------------------------------------------------
    Adding new social profile fields to the user profile editor
-----------------------------------------------------------------------------------------------------------*/

function sabox_extra_fields( $extra_fields ) {

    unset($extra_fields['aim']);
    unset($extra_fields['jabber']);
    unset($extra_fields['yim']);

    foreach ( Sabox_Social_Icons::$sabox_social_icons_array as $sabox_social_id => $sabox_social_name ) {
        $extra_fields[$sabox_social_id] = $sabox_social_name;
    }
    return $extra_fields;
}

add_filter( 'user_contactmethods', 'sabox_extra_fields' );