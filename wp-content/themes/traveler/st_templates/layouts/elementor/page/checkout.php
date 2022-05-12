<?php
get_header();
wp_enqueue_script('checkout-js');
wp_enqueue_script('checkout-modern');
wp_enqueue_media();
wp_enqueue_style('wb-form-builder');
wp_enqueue_script('wb-form-builder-js');
?>
    <div id="st-content-wrapper">
        <div class="st-breadcrumb">
            <div class="container">
                <ul>
                    <li>
                        <a href="<?php echo site_url('/'); ?>"><?php echo __('Home', 'traveler'); ?></a>
                    </li>
                    <li>
                        <span><?php echo get_the_title(); ?></span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container">
            <div class="st-checkout-page">
                <?php if (!STCart::check_cart()): ?>
                    <div class="alert alert-danger">
                        <p><?php esc_html_e('Sorry! Your cart is currently empty.', 'traveler') ?></p>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php
                        $is_guest_booking = st()->get_option('is_guest_booking', "on");
                        $is_user_logged_in = is_user_logged_in();
                        if ((!$is_user_logged_in && $is_guest_booking == 'on') || $is_user_logged_in) { ?>
                            <div class="col-lg-4 col-md-4 order-1 order-sm-2">
                                <h3 class="title">
                                    <?php echo __('Your Booking', 'traveler'); ?>
                                </h3>
                                <div class="cart-info st-border-radius" id="cart-info">
                                    <?php
                                    $all_items = STCart::get_items();
                                    if (!empty($all_items) and is_array($all_items)) {
                                        foreach ($all_items as $key => $value) {
                                            do_action('st_cart_item_html_' . $key);
                                            if ($key === 'car_transfer') {

                                                $transfer = new STCarTransfer();
                                                echo balanceTags($transfer->get_cart_item_html($key));
                                                break;
                                            } else {
                                                if (get_post_status($key)) {
                                                    $post_type = get_post_type($key);
                                                    switch ($post_type) {
                                                        case "st_hotel":
                                                            if (class_exists('STHotel')) {
                                                                $hotel = new STHotel();
                                                                echo balanceTags($hotel->get_cart_item_html($key));
                                                            }
                                                            break;
                                                        case "hotel_room":
                                                            if (class_exists('STRoom')) {
                                                                $room = new STRoom();
                                                                echo balanceTags($room->get_cart_item_html($key));
                                                            }
                                                            break;
                                                        case "st_cars":
                                                            if (class_exists('STCars')) {
                                                                $cars = new STCars();
                                                                echo balanceTags($cars->get_cart_item_html($key));
                                                            }
                                                            break;
                                                        case "st_tours":
                                                            if (class_exists('STTour')) {
                                                                $tours = new STTour();
                                                                echo balanceTags($tours->get_cart_item_html($key));
                                                            }
                                                            break;
                                                        case "st_rental":
                                                            if (class_exists('STRental')) {
                                                                $object = STRental::inst();
                                                                echo balanceTags($object->get_cart_item_html($key));
                                                            }
                                                            break;
                                                        case "st_activity":
                                                            if (class_exists('STActivity')) {
                                                                $object = STActivity::inst();
                                                                echo balanceTags($object->get_cart_item_html($key));
                                                            }
                                                            break;
                                                        case "st_flight":
                                                            if (class_exists('ST_Flight_Checkout')) {
                                                                echo ST_Flight_Checkout::inst()->get_cart_item_html($key);
                                                            }
                                                            break;
                                                        case "car_transfer":
                                                            if (class_exists('STCarTransfer')) {
                                                                echo STCarTransfer::inst()->get_cart_item_html($key);
                                                            }
                                                            break;
                                                    }
                                                }
                                            }

                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8 order-2 order-sm-1">
                                <h3 class="title">
                                    <?php echo __('Booking Submission', 'traveler'); ?>
                                </h3>
                                <div class="check-out-form">
                                    <div class="entry-content">
                                        <?php
                                        while (have_posts()) {
                                            the_post();
                                            the_content();
                                        }
                                        ?>
                                    </div>
                                    <form id="cc-form" class="" method="post" onsubmit="return false">
                                        <?php echo st()->load_template('layouts/modern/checkout/check_out') ?>
                                        <?php
                                        do_action('st_more_fields_after_checkout_form');
                                        ?>
                                    </form>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="col-lg-12 col-md-12">
                                <h3 class="title">
                                    <?php echo __('You need to login to checkout', 'traveler'); ?>
                                </h3>
                                <div class="check-out-form">
                                    <div class="entry-content">
                                        <a data-target="#st-login-form" data-toggle="modal" class="btn btn-green  upper"
                                           type="submit"><?php echo __('Login', 'traveler'); ?></a>
                                        <a data-target="#st-register-form" data-toggle="modal"
                                           class="btn btn-green upper"
                                           type="submit"><?php echo __('Register', 'traveler'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <span class="hidden st_template_checkout"></span>
<?php
get_footer();
