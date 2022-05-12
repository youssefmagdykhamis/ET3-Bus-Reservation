<?php
$style = get_post_meta(get_the_ID(), 'rs_style', true);
if (empty($style))
    $style = 'grid';

global $wp_query, $st_search_query;
if ($st_search_query) {
    $query = $st_search_query;
} else $query = $wp_query;

$map_pos = get_post_meta(get_the_ID(), 'rs_hotel_map_pos', true);
if(empty($map_pos))
    $map_pos = 'right';
?>
<div class="row service-list-wrapper page-half-map map-<?php echo esc_attr($map_pos); ?>">
    <?php if($map_pos == 'left'){ ?>
        <div class="col-lg-6 col-md-6 col-right">
            <?php echo st()->load_template('layouts/elementor/common/loader', 'map'); ?>
            <div class="map-title d-md-none"><?php echo __('Map', 'traveler'); ?> <span class="close-half-map"><?php echo TravelHelper::getNewIcon('Ico_close', '#A0A9B2', '20px', '20px'); ?></span></div>
            <div id="map-search-form" class="map-full-height" data-disablecontrol="true" data-showcustomcontrol="true"></div>
        </div>
    <?php } ?>

    <div class="col-lg-6 col-md-6 col-left">
        <?php echo st()->load_template('layouts/elementor/hotel/elements/toolbar', '', array('style' => $style)); ?>
        <div id="modern-search-result" class="modern-search-result st-scrollbar" data-format="halfmap" data-layout="2">
            <?php echo st()->load_template('layouts/elementor/common/loader', 'content'); ?>
            <div class="row service-list-wrapper <?php echo ($style == 'list') ? 'list-style' : ''; ?>">
                <?php
                if($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        echo st()->load_template('layouts/elementor/hotel/loop/halfmap', $style, array('item_row'=> 2));
                    }
                }else{
                    echo '<div class="col-12">';
                    echo st()->load_template('layouts/elementor/hotel/elements/none');
                    echo '</div>';
                }
                wp_reset_query();
                ?>
            </div>
        </div>

        <div class="pagination moderm-pagination" id="moderm-pagination">
            <?php echo TravelHelper::paging(false, false); ?>
            <span class="count-string">
                <?php
                if (!empty($st_search_query)) {
                    $query = $st_search_query;
                }
                if ($query->found_posts):
                    $page = get_query_var('paged');
                    $posts_per_page = st()->get_option( 'hotel_posts_per_page', 12 );
                    if (!$page) $page = 1;
                    $last = $posts_per_page * ($page);
                    if ($last > $query->found_posts) $last = $query->found_posts;
                    echo sprintf(__('%d - %d of %d ', 'traveler'), $posts_per_page * ($page - 1) + 1, $last, $query->found_posts );
                    echo ( $query->found_posts == 1 ) ? __( 'Hotel', 'traveler' ) : __( 'Hotels', 'traveler' );
                endif;
                ?>
            </span>
        </div>
    </div>
    <?php if($map_pos == 'right'){ ?>
    <div class="col-lg-6 col-md-6 col-right">
        <?php echo st()->load_template('layouts/elementor/common/loader', 'map'); ?>
        <div class="map-title d-md-none"><?php echo __('Map', 'traveler'); ?> <span class="close-half-map"><?php echo TravelHelper::getNewIcon('Ico_close', '#A0A9B2', '20px', '20px'); ?></span></div>
        <div id="map-search-form" class="map-full-height" data-disablecontrol="true" data-showcustomcontrol="true"></div>
    </div>
    <?php } ?>
</div>
