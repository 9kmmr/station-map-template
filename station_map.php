<?php

/**
 * Plugin Name: Station Map Template
 * Description: This plugin created Station Map template & view function for station custom post type
 * Author URI: https://wordpress.org
 * Author: yourmindhasgone
 * Plugin Url: https://wordpress.org
 * Version: 1.0.1
 * Text Domain: station-map
 */



if (!function_exists('station_map_load_text_domain')) {
    function station_map_load_text_domain()
    {

        load_plugin_textdomain(
            'station-map',
            false,
            plugin_dir_path(__FILE__)  . '/languages/'
        );
    }
}

add_action('plugins_loaded', 'station_map_load_text_domain');


add_shortcode('station_map_template', 'station_map_template_handle');
if (!function_exists('station_map_template_handle')) {

    function station_map_template_handle($atts)
    {
        $atts = shortcode_atts(
            array(
                'order' => 'desc',
                'orderby' => 'date',
                'num' => -1
            ),
            $atts
        );

        ob_start();
        //code...
        include   plugin_dir_path(__FILE__) . 'station-map-template-page.php';
        $output_string = ob_get_contents();
        ob_end_clean();
        return  $output_string;
    }
}

add_action('wp_enqueue_scripts', 'station_map_enqueue_scripts_handle');
if (!function_exists('station_map_enqueue_scripts_handle')) {

    function station_map_enqueue_scripts_handle()
    {
        wp_enqueue_style('leaflet-css',  plugin_dir_url(__FILE__) . 'leaflet.css', [], '1.0.1', 'all');
        wp_enqueue_style('station_map_style',  plugin_dir_url(__FILE__) . 'station-map-style.css', [], '1.0.1', 'all');

        wp_enqueue_script('leaflet-js', plugin_dir_url(__FILE__) .   'leaflet.js', array(), '1.0.1', true);
        wp_enqueue_script('station_map_script', plugin_dir_url(__FILE__) . 'station-map-js.js', [], '1.0.1', true);
    }
}


add_action('wp_ajax_nopriv_get_station_posts', 'get_station_posts_handle', 10, 1);
add_action('wp_ajax_get_station_posts', 'get_station_posts_handle', 10, 1);
if (!function_exists('get_station_posts_handle')) {
    function get_station_posts_handle()
    {
        $all_station = [];
        if (post_type_exists('station')) {

            $all_station_args = [
                'numberposts' => -1,
                'order' => $_GET['order'] ? $_GET['order'] : "desc",
                'orderby' => $_GET['orderby'] ? $_GET['orderby'] : "date",
                'post_type' => 'station'

            ];
            $all_station = get_posts($all_station_args);
            $favicon = get_site_icon_url();
            if (function_exists('get_field')) {

                $all_station = array_map(function ($post) use ($favicon) {

                    $post_thumb = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'thumbnail');

                    return [
                        "thumbnail" =>  $post_thumb ? $post_thumb : $favicon,
                        "title" => $post->post_title,
                        "location" => get_field('location', $post->ID),
                        "information" =>  get_field('information', $post->ID, false, false),
                        "homepage" =>  get_field('homepage', $post->ID),
                        "telephone" =>  get_field('telephone', $post->ID),
                        "email" =>  get_field('email', $post->ID),
                    ];
                }, $all_station);
            }
        }

        echo json_encode($all_station);

        exit();
    }
}
