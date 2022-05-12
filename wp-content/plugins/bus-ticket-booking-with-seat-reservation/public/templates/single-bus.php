<?php
get_header();
the_post();
$id = get_the_id();
$values = get_post_custom($id);
?>
    <div class="mage mage_single_bus_search_page" data-busId="<?php echo $id; ?>">
        <?php do_action('woocommerce_before_single_product'); ?>
        <div class="post-content-wrap">
        <?php echo the_content();?>
        </div>
        <div class="mage_default">
            <div class="flexEqual">
                <div class="mage_xs_full"><?php the_post_thumbnail('full'); ?></div>
                <div class="ml_25 mage_xs_full">
                    <div class="mage_default_bDot">
                        <h4><?php the_title(); ?><small>( <?php echo $values['wbtm_bus_no'][0]; ?> )</small></h4>
                        <h6 class="mar_t_xs"><strong><?php _e('Bus Type :', 'bus-ticket-booking-with-seat-reservation'); ?></strong><?php echo mage_bus_type(); ?></h6>
                        <h6 class="mar_t_xs"><strong><?php _e('Passenger Capacity :', 'bus-ticket-booking-with-seat-reservation'); ?></strong><?php echo mage_bus_total_seat_new(); ?></h6>
                        <?php if (mage_bus_run_on_date(false) && isset($_GET['bus_start_route']) && ($_GET['bus_end_route']) && ($_GET['j_date'])) { ?>
                            <h6 class="mar_t_xs">
                                <span><?php _e('Fare :', 'bus-ticket-booking-with-seat-reservation'); ?></span>
                                <strong><?php echo wc_price(mage_bus_seat_price($id,mage_bus_isset('bus_start_route'), mage_bus_isset('bus_end_route'),false)); ?></strong>/
                                <span><?php _e('Seat', 'bus-ticket-booking-with-seat-reservation'); ?></span>
                            </h6>
                        <?php } ?>
                    </div>
                    <div class="flexEqual_mar_t mage_bus_drop_board">
                        <div class="mage_default_bDot">
                            <h5><?php _e('Boarding Point', 'bus-ticket-booking-with-seat-reservation'); ?></h5>
                            <ul class="mage_list mar_t_xs">
                                <?php
                                $start_stops = maybe_unserialize(get_post_meta(get_the_id(), 'wbtm_bus_bp_stops', true));
                                foreach ($start_stops as $route) {
                                    echo '<li><span class="fa fa-map-marker"></span>' . $route['wbtm_bus_bp_stops_name'] . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="mage_default_bDot">
                            <h5><?php _e('Dropping Point', 'bus-ticket-booking-with-seat-reservation'); ?></h5>
                            <ul class="mage_list mar_t_xs">
                                <?php
                                $end_stops = maybe_unserialize(get_post_meta(get_the_id(), 'wbtm_bus_next_stops', true));
                                foreach ($end_stops as $route) {
                                    echo '<li><span class="fa fa-map-marker"></span>' . $route['wbtm_bus_next_stops_name'] . '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mage_default mage_form_inline">

            <?php

            $global_target = $wbtmmain->bus_get_option('search_target_page', 'label_setting_sec') ? get_post_field('post_name', $wbtmmain->bus_get_option('search_target_page', 'label_setting_sec')) : 'bus-search-list';
            if(isset($params)) {
                $target = $params['search-page'] ? $params['search-page'] : $global_target;
            } else {
                $target = $global_target;
            }

            mage_bus_search_form_only(true, $target); ?>
        </div>
        <?php
        //  if (mage_bus_run_on_date(false) && isset($_GET['bus_start_route']) && ($_GET['bus_end_route']) && ($_GET['j_date'])) { 
         if (isset($_GET['bus_start_route']) && ($_GET['bus_end_route']) && ($_GET['j_date'])) {
            
            $start = $_GET['bus_start_route'];
            $end = $_GET['bus_end_route'];
            $j_date = $_GET['j_date'];
            $j_date = mage_convert_date_format($j_date, 'Y-m-d');
            $r_date = isset($_GET['r_date']) ? $_GET['r_date'] : null;
            if($r_date) {
                $r_date = mage_convert_date_format($r_date, 'Y-m-d');
            }
            $check_has_price = mage_bus_seat_price($id, $start, $end, false);
            $has_bus = false;
            $has_bus_return = false;

            $bus_bp_array = get_post_meta($id, 'wbtm_bus_bp_stops', true) ? get_post_meta($id, 'wbtm_bus_bp_stops', true) : [];
            $bus_bp_array = maybe_unserialize($bus_bp_array);

            if($bus_bp_array) {
                $has_bus = mage_single_bus_show($id, $start, $end, $j_date, $bus_bp_array, $has_bus);
                if($r_date) {
                    $has_bus_return = mage_single_bus_show($id, $end, $start, $r_date, $bus_bp_array, $has_bus_return);
                }
            }

            // Final
            mage_next_date_suggestion(false, true);
            if ($has_bus && $check_has_price) {

                mage_bus_search_item(false, $id);

            } else {
                echo '<div class="wbtm-warnig">';
                _e('This bus available only in the particular date. :) ', 'bus-ticket-booking-with-seat-reservation');
                echo '</div>';
            }

            if($r_date) {
                if ($has_bus_return && $check_has_price) {
                    echo '<div class="wbtm_return_header">'.__("Return Trip", "bus-ticket-booking-with-seat-reservation").'</div>';
                    mage_bus_search_item(true, $id);
    
                } else {
                    echo '<div class="wbtm-warnig">';
                    _e('This bus available only in the particular date. :) ', 'bus-ticket-booking-with-seat-reservation');
                    echo '</div>';
                }
            }
        } 
        ?>
    </div>


<?php
get_footer();