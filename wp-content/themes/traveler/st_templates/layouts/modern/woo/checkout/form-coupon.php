<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.4.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! wc_coupons_enabled() ) {
	return;
}
?>
<div class="st-coupon-wrapper">
    <?php
$info_message = apply_filters( 'woocommerce_checkout_coupon_message', __( 'Have a coupon?', 'traveler' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'traveler' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
?>

<form class="checkout_coupon" method="post" style="display:none">

	<p class="form-row form-row-first">
		<input type="text" name="coupon_code" class="input-text" placeholder="<?php _e( 'Coupon code', 'traveler' ); ?>" id="coupon_code" value="" />
	</p>

	<p class="form-row form-row-last">
		<input type="submit" class="btn btn-primary" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'traveler' ); ?>" />
	</p>

	<div class="clear"></div>
</form>
</div>
