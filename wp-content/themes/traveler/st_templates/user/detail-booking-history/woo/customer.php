<div class="info">
    <div class="row">
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('First name ' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_first_name', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('Last name ' , 'traveler') ; ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_last_name', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('Email ' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_email', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('Phone ' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_phone', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('Address Line 1' , 'traveler' ) ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_address_1', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('Address Line 2' , 'traveler' ) ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_address_2', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('City' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_city', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('State/Province/Region' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_state', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('ZIP code/Postal code' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_postcode', true) ?>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item_booking_detail">
                <strong><?php echo __('Country' , 'traveler') ;  ?></strong>:
                <?php echo get_post_meta($order_id, '_billing_country', true) ?>
            </div>
        </div>
    </div>

</div>