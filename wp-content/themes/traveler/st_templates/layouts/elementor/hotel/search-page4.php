<?php
get_header();
wp_enqueue_script('filter-hotel');
?>
    <div id="st-content-wrapper" class="search-result-page layout2">
        <?php
        echo st()->load_template('layouts/elementor/hotel/elements/banner');
        ?>
        <div class="full-map d-none d-sm-none d-md-block">
            <div class="search-form-wrapper">
                <div class="container">
                    <div class="row st-padding-col">
                        <?php echo st()->load_template('layouts/elementor/hotel/elements/search-form'); ?>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="st-results st-hotel-result">
            <div class="container">
                <?php
                echo st()->load_template('layouts/elementor/hotel/elements/top-filter/top-filter4');
                $query           = array(
                    'post_type'      => 'st_hotel' ,
                    'post_status'    => 'publish' ,
                    's'              => '' ,
                    'orderby' => 'post_modified',
                    'order'   => 'DESC',
                );
                global $wp_query , $st_search_query;

                $current_lang = TravelHelper::current_lang();
                $main_lang = TravelHelper::primary_lang();
                if (TravelHelper::is_wpml()) {
                    global $sitepress;
                    $sitepress->switch_lang($main_lang, true);
                }

                $hotel = STHotel::inst();
                $hotel->alter_search_query();
                query_posts( $query );
                $st_search_query = $wp_query;
                $hotel->remove_alter_search_query();
                wp_reset_query();

                if (TravelHelper::is_wpml()) {
                    global $sitepress;
                    $sitepress->switch_lang($current_lang, true);
                }

                echo st()->load_template('layouts/elementor/hotel/elements/content4'); ?>
            </div>
        </div>
        <input id="st-layout-fullwidth" value="1" type="hidden"/>
    </div>
<?php
echo st()->load_template('layouts/elementor/hotel/elements/popup/date');
echo st()->load_template('layouts/elementor/hotel/elements/popup/guest');
get_footer();