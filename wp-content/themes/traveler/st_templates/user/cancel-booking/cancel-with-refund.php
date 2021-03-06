<?php
/**
*@since 1.2.8
*	cancel with refund
**/
?>
<div class="clearfix">
	<strong><?php echo __('Order ID: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['order_id']); ?></em></span>
</div>
<div class="mt10 clearfix">
	<strong><?php echo __('Refund Amount: ', 'traveler'); ?></strong><span class=""><span class="text-danger"><em><strong><?php echo TravelHelper::format_money_raw( $cancel_data['refunded'], $cancel_data['currency'] ); ?></strong></em></span></span>
</div>
<div class="mt10 clearfix">
	<strong><?php echo __('Reason: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['detail']); ?></em></span>
</div>
<div class="mt10 clearfix">
	<strong><?php echo __('Refund Status: ', 'traveler'); ?></strong><button class="btn btn-primary btn-xs"><?php echo esc_html($cancel_data['cancel_refund_status']); ?></button>
</div>

<?php 
	if( !empty( $cancel_data['your_paypal'] ) ):
?>
<div class="alert alert-info mt20" role="alert">
	<div class="clearfix">
		<strong><?php echo __('Paypal Email: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['your_paypal']['paypal_email']); ?></em></span>
	</div>
</div>
<?php
$payment_method  = get_post_meta($cancel_data['order_id'] , 'payment_method' , true);
if($payment_method == "st_paypal_adaptivepayment"){
    $st_payment_refund = get_post_meta( $cancel_data['order_id'], 'st_payment_refund', true);
    ?>
    <div>
        <button type="button" data-order-id="<?php echo esc_html($cancel_data['order_id']) ?>" class="refund_via_paypal_adaptive next btn btn-info" <?php if($st_payment_refund == 'yes') echo 'disabled'; ?> >
            <?php echo __('Refund via Paypal', 'traveler'); ?>
            <i class="fa fa-spinner fa-spin"></i>
        </button>
        <div class="message">
            <?php if($st_payment_refund == 'yes'){?>
            <div class="alert alert-success mt20">
                <?php  esc_html_e("Refund via PayPal Success",'traveler'); ?>
            </div>
            <?php } ?>
        </div>
    </div>
    <?php
} ?>
<?php elseif( !empty( $cancel_data['your_bank'] ) ): ?>
<div class="alert alert-info mt20" role="alert">	
	<div class=" clearfix">
		<strong><?php echo __('Acount Name: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['your_bank']['account_name']); ?></em></span>
	</div>
	<div class="mt10 clearfix">
		<strong><?php echo __('Acount Number: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['your_bank']['account_number']); ?></em></span>
	</div>
	<div class="mt10 clearfix">
		<strong><?php echo __('Bank Name: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['your_bank']['bank_name']); ?></em></span>
	</div>
	<div class="mt10 clearfix">
		<strong><?php echo __('Swift Code: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['your_bank']['swift_code']); ?></em></span>
	</div>
</div>	
<?php elseif( !empty( $cancel_data['your_stripe'] ) ): ?>
<div class="alert alert-info mt20" role="alert">
	<div class="clearfix">
		<strong><?php echo __('[Stripe] Transaction ID: ', 'traveler'); ?></strong><span class=""><em><?php echo esc_html($cancel_data['your_stripe']['transaction_id']); ?></em></span>
	</div>
</div>
<?php endif; ?>