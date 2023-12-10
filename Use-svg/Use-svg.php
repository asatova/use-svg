<?php

/**
 * Plugin Name: Use SVG
 * Description: With this plugin, you can easily use svg images in your wordpress project!
 * Plugin URI:  https://t.me/asatova_blog/181
 * Author URI:  https://t.me/asatova_portfolio
 * Author:      Zarnigor
 * Version:     1.0
 *

 */

add_filter( 'upload_mimes', 'svg_upload_allow' );

# Добавляет SVG в список разрешенных для загрузки файлов.
function svg_upload_allow( $mimes ) {
    $mimes['svg']  = 'image/svg+xml';

    return $mimes;
}
add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

# Исправление MIME типа для SVG файлов.
function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){

    // WP 5.1 +
    if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) ){
        $dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
    }
    else {
        $dosvg = ( '.svg' === strtolower( substr( $filename, -4 ) ) );
    }

    // mime тип был обнулен, поправим его
    // а также проверим право пользователя
    if( $dosvg ){

        // разрешим
        if( current_user_can('manage_options') ){

            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        // запретим
        else {
            $data['ext']  = false;
            $data['type'] = false;
        }

    }

    return $data;
}
