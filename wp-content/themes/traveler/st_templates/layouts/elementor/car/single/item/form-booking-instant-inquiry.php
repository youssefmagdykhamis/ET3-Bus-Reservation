<div class="form-book-wrapper st-border-radius relative">
    <?php echo st()->load_template('layouts/elementor/common/loader'); ?>
    <div class="form-head">
        <?php echo wp_kses(sprintf(__('<span class="price">%s</span><span class="unit">/%s</span>', 'traveler'), TravelHelper::format_money($cars_price), strtolower(STCars::get_price_unit('label'))), ['span' => ['class' => []]]) ?>
    </div>
    <nav>
        <ul class="nav nav-tabs nav-fill-st" id="nav-tab" role="tablist">
            <li><a class="active" id="nav-book-tab" data-bs-toggle="tab" href="#nav-book" role="tab" aria-controls="nav-home" aria-selected="true"><?php echo esc_html__( 'Book', 'traveler' ) ?></a></li>
            <li><a id="nav-inquirement-tab" data-bs-toggle="tab" href="#nav-inquirement" role="tab" aria-controls="nav-profile" aria-selected="false"><?php echo esc_html__( 'Inquiry', 'traveler' ) ?></a></li>
        </ul>
    </nav>
    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-book" role="tabpanel" aria-labelledby="nav-book-tab">
            <?php if (empty($car_external) || $car_external == 'off') { ?>
                <form id="form-booking-inpage" method="post" action="#booking-request" class="car-booking-form">
                    <input type="hidden" name="action" value="cars_add_to_cart">
                    <input type="hidden" name="item_id" value="<?php echo get_the_ID(); ?>">
                    <input type="hidden" name="car_type" value="<?php echo  $unit = st()->get_option('cars_price_unit', 'day'); ?>">
                    <?php echo st()->load_template('layouts/modern/car/elements/search/location-single', ''); ?>
                    <?php echo st()->load_template('layouts/modern/car/elements/search/date', ''); ?>
                    <?php echo st()->load_template('layouts/modern/car/elements/search/extra', ''); ?>
                    <div class="submit-group">
                        <button class="btn btn-green btn-large btn-full upper btn-book-ajax"
                                type="submit"
                                name="submit">
                            <?php echo esc_html__('Book Now', 'traveler') ?>
                            <i class="fa fa-spinner fa-spin d-none"></i>
                        </button>
                        <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID();?>" name="st_send_message" value="<?php echo __('Send message', 'traveler');?>">
                    </div>
                    <div class="message-wrapper mt30">
                        <!-- <?php echo STTemplate::message() ?> -->
                    </div>
                </form>
            <?php } else { ?>
                <div class="submit-group mb30">
                    <a href="<?php echo esc_url($car_external_link); ?>"
                        class="btn btn-green btn-large btn-full upper"><?php echo esc_html__('Book Now', 'traveler'); ?></a>
                    <form id="form-booking-inpage" method="post" action="#booking-request" class="activity-booking-form">
                        <input type="hidden" name="action" value="activity_add_to_cart">
                        <input type="hidden" name="item_id" value="<?php echo get_the_ID(); ?>">
                        <?php
                        $current_calendar = TravelHelper::get_current_available_calendar(get_the_ID());
                        $current_calendar_reverb = date('m/d/Y', strtotime($current_calendar));

                        $start    = STInput::request( 'check_in', date( TravelHelper::getDateFormat(), strtotime($current_calendar) ) );
                        $end      = STInput::request( 'check_out', date( TravelHelper::getDateFormat(), strtotime($current_calendar) ) );
                        $date = STInput::request('date', date('d/m/Y h:i a', strtotime($current_calendar)). '-'. date('d/m/Y h:i a', strtotime($current_calendar)));
                        ?>

                        <input type="hidden" class="check-in-input"
                                value="<?php echo esc_attr( $start ) ?>" name="check_in">
                        <input type="hidden" class="check-out-input"
                                value="<?php echo esc_attr( $end ) ?>" name="check_out">
                        <input type="hidden" class="check-in-out-input"
                                value="<?php echo esc_attr( $date ) ?>" name="check_in_out"
                                data-action="st_get_availability_activity_frontend"
                                data-tour-id="<?php the_ID(); ?>" data-posttype="st_activity">
                        <?php
                        /*Starttime*/
                        $starttime_value = STInput::request('starttime_tour', '');
                        ?>

                        <div class="form-group form-more-extra st-form-starttime" <?php echo ($starttime_value != '') ? '' : 'style="display: none"' ?>>
                            <input type="hidden" data-starttime="<?php echo esc_attr($starttime_value); ?>"
                                    data-checkin="<?php echo esc_attr($start); ?>" data-checkout="<?php echo esc_attr($end); ?>"
                                    data-tourid="<?php echo get_the_ID(); ?>" id="starttime_hidden_load_form" data-posttype="st_activity"/>
                        </div>
                        <input style="display:none;" type="submit" class="btn btn-default btn-send-message" data-id="<?php echo get_the_ID();?>" name="st_send_message" value="<?php echo __('Send message', 'traveler');?>">
                    </form>
                </div>
            <?php } ?>
        </div>
        <div class="tab-pane fade " id="nav-inquirement" role="tabpanel" aria-labelledby="nav-inquirement-tab">
            <?php echo st()->load_template( 'email/email_single_service' ); ?>
        </div>
    </div>
</div>