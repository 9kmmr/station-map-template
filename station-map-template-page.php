<?php
// exit if call directly
if (!defined('ABSPATH')) {
    exit;
}

$favicon = get_site_icon_url();




if (post_type_exists('station')) {

    $all_station_args = [
        'numberposts' => $atts['num'],
        'order' => $atts['order'],
        'orderby' => $atts['orderby'],
        'post_type' => 'station',
        'suppress_filters' => 0

    ];
    $all_station = get_posts($all_station_args);

    if (function_exists('get_field')) {

        $all_station = array_map(function ($post) use ($favicon) {

            return [
                "thumbnail" =>  has_post_thumbnail($post->ID) ? wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'thumbnail') : $favicon,
                "title" => $post->post_title,
                "location" => get_field('location', $post->ID),
                "information" =>  get_field('information', $post->ID, false, false),
                "homepage" =>  get_field('homepage', $post->ID),
                "telephone" =>  get_field('telephone', $post->ID),
                "email" =>  get_field('email', $post->ID),
            ];
        }, $all_station);
    }

?>


    <div class="map_station_container">
        <div class="map_station_map_section">
            <div class="map_station_map" id="map_station_map">

            </div>

        </div>
        <div class="map_station_main_content">
            <?php if (count($all_station)) {
                foreach ($all_station as $key => $station) {


            ?>
                    <div class="map_station_sec">
                        <div class="map_station-items">
                            <div>
                                <a class="ms_button ms_button_view" title="<?= __("View in Map", 'station-map') ?>" target="_blank" href="https://maps.google.de/maps?q=<?= $station['location']['lat'] ?>, <?= $station['location']['lng'] ?>">
                                    <i class="fa fa-map fa-3x"></i>
                                </a>
                            </div>
                        </div>
                        <div class="map_station-items map_station_infos">
                            <div class="map_station_infos_items">
                                <div>

                                    <?= esc_html(isset($station['information']) ? $station['information'] : "") ?>
                                </div>
                                <div>
                                    <?= esc_html(isset($station['location']) ? $station['location']['address'] : "") ?>
                                </div>
                                <div>
                                    <a href="<?= esc_url(isset($station['homepage']) ? $station['homepage'] : "") ?>"><?= esc_url(isset($station['homepage']) ? preg_replace('#^https?://#i', '', $station['homepage']) : "") ?></a>
                                </div>
                            </div>
                            <div class="map_station_infos_items">
                                <div>
                                    <?= esc_html(isset($station['telephone']) ? $station['telephone'] : "") ?>
                                </div>
                                <div>
                                    <a href="<?= esc_html(isset($station['email']) ? ("mailto:" . $station['email']) : "") ?>"><?= esc_html(isset($station['email']) ? $station['email'] : "") ?></a>
                                </div>
                            </div>

                        </div>
                        <div class="map_station-items map_station-itemicon">
                            <div class="map_station_icon">
                                <img src="<?= isset($station['thumbnail']) ? $station['thumbnail'] : "" ?>" class="ms_img ms_rounded-circle" alt="<?= esc_html(isset($station['title']) ? $station['title'] : $station['post_title']) ?>">
                            </div>
                        </div>
                    </div>

            <?php
                }
            } ?>

        </div>
    </div>

<?php
}
?>
<script>
    var map_station_order = '<?php $atts['order'] ?>';
    var map_station_orderby = '<?php $atts['orderby'] ?>';
    var map_station_url = '<?php echo esc_url(plugin_dir_url(__FILE__)) ?>'
</script>