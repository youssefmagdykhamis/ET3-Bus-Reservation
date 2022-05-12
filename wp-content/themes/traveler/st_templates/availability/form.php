<?php
    wp_enqueue_script( 'bootstrap-datepicker.js' ); wp_enqueue_script( 'bootstrap-datepicker-lang.js' );
?>
<?php $post_id = intval($_GET['id']);
?>
<span class="hidden st_partner_avaiablity <?php echo STInput::get('sc') ?> "></span>
<div class="row calendar-wrapper template-user" data-post-id="<?php echo esc_html($post_id); ?>">
    <div class="col-xs-12 col-md-8 calendar-wrapper-inner">
        <div class="overlay-form"><i class="fa fa-refresh text-color"></i></div>
        <div class="calendar-content" id="calendar-content" data-price-by-per-person="<?php echo esc_attr( get_post_meta( $post_id, 'price_by_per_person', true ) == 'on' ? 'on' : 'off') ?>">
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="calendar-form">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="calendar_check_in"><?php echo __('Check In', 'traveler'); ?></label>
                        <input readonly="readonly" type="text" class="form-control date-picker" placeholder="<?php echo __('Check In', 'traveler'); ?>" name="calendar_check_in" id="calendar_check_in">
                    </div>
                </div>
                <div class="col-sn-12 col-xs-12">
                    <div class="form-group">
                        <label for="calendar_check_out"><?php echo __('Check Out', 'traveler'); ?></label>
                        <input readonly="readonly" type="text" class="form-control date-picker" placeholder="<?php echo __('Check Out', 'traveler'); ?>" name="calendar_check_out" id="calendar_check_out">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group"<?php echo (get_post_meta( $post_id, 'price_by_per_person', true ) == 'on') ? ' style="display: none;"' : '' ?>>
                        <label for="calendar_price"><?php echo __('Price', 'traveler'); ?></label>
                        <input type="text" name="calendar_price" id="calendar_price" placeholder="<?php echo __('Price', 'traveler'); ?>" class="form-control">
                    </div>
                    <div class="row form-group"<?php echo (get_post_meta( $post_id, 'price_by_per_person', true ) != 'on') ? ' style="display: none;"' : '' ?>>
                        <div class="col-xs-6">
                            <label for="calendar_adult_price"><strong><?php echo __('Adult Price', 'traveler'); ?></strong></label>
                            <input type="text" name="calendar_adult_price" id="calendar_adult_price" class="form-control" placeholder="<?php echo __('Adult Price', 'traveler'); ?>">
                        </div>
                        <div class="col-xs-6">
                            <label for="calendar_child_price"><strong><?php echo __('Child Price', 'traveler'); ?></strong></label>
                            <input type="text" name="calendar_child_price" id="calendar_child_price" class="form-control" placeholder="<?php echo __('Child Price', 'traveler'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label for="calendar_status"><?php echo __('Status', 'traveler'); ?></label>
                        <select name="calendar_status" id="calendar_status" class="form-control">
                            <option value="available"><?php echo __('Available', 'traveler'); ?></option>
                            <option value="unavailable"><?php echo __('Unavailble', 'traveler'); ?></option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-message">
                    <p class="text-danger"></p>
                </div>
            </div>
            <div class="form-group">
                <input type="hidden" name="calendar_post_id" value="<?php echo esc_html($post_id); ?>">
                <input type="submit" id="calendar_submit" class="btn btn-primary" name="calendar_submit" value="<?php echo __('Update', 'traveler'); ?>">
                <?php do_action('traveler_after_form_submit_hotel_calendar'); ?>
            </div>
        </div>
    </div>
    <?php do_action('traveler_after_form_hotel_calendar'); ?>
</div>
