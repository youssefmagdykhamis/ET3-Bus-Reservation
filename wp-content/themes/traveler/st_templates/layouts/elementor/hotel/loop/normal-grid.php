<?php
    $post_id      = get_the_ID();
    $post_translated = TravelHelper::post_translated($post_id);
    $thumbnail_id = get_post_thumbnail_id($post_translated);
    $hotel_star   = (int)get_post_meta( $post_translated, 'hotel_star', true );
    $address      = get_post_meta( $post_translated, 'address', true );
    $review_rate  = STReview::get_avg_rate();
    $price        = STHotel::get_price();
    $count_review = get_comment_count($post_translated)['approved'];
    $class='col-12 col-sm-6 col-md-3';
    if(!empty($item_row) && ($item_row == 3)){
        $class='col-12 col-sm-6 col-md-4';
    }elseif(!empty($item_row) && ($item_row == 4)){
        $class='col-12 col-sm-6 col-md-3';
    }
    elseif(!empty($item_row) && ($item_row == 2)){
        $class='col-12 col-sm-6 col-md-6';
    }
    $class_image='image-feature';
    if( !empty($slider) && $slider === 'slider'){
        $class = 'swiper-slide';
        $class_image = 'swiper-lazy';
    }
    $url=st_get_link_with_search(get_permalink($post_translated),array('start','end','date','adult_number','child_number'),$_GET);
?>

<div class="<?php echo esc_attr($class); ?>" itemscope itemtype="https://schema.org/Hotel">
    <div class="item service-border st-border-radius">
        <div class="featured-image">
            <?php
                $is_featured = get_post_meta( $post_translated, 'is_featured', true );
                if ( $is_featured == 'on' ) {
                    ?>
                    <?php echo STFeatured::get_featured(); ?>
                    <?php
                }
            ?>
            <?php if (is_user_logged_in()) { ?>
                <?php $data = STUser_f::get_icon_wishlist(); ?>
                <div class="service-add-wishlist login <?php echo ($data['status']) ? 'added' : ''; ?>"
                    data-id="<?php echo get_the_ID(); ?>" data-type="<?php echo get_post_type(get_the_ID()); ?>"
                    title="<?php echo ($data['status']) ? __('Remove from wishlist', 'traveler') : __('Add to wishlist', 'traveler'); ?>">
                    <i class="fa fa-heart"></i>
                    <div class="lds-dual-ring"></div>
                </div>
            <?php } else { ?>
                <a href="javascript: void(0)" class="login" data-bs-toggle="modal" data-bs-target="#st-login-form">
                    <div class="service-add-wishlist" title="<?php echo __('Add to wishlist', 'traveler'); ?>">
                        <i class="fa fa-heart"></i>
                        <div class="lds-dual-ring"></div>
                    </div>
                </a>
            <?php } ?>
            <a href="<?php echo esc_url($url); ?>">
                <img itemprop="photo" src="<?php echo wp_get_attachment_image_url( $thumbnail_id, array(450, 300) ); ?>" alt="<?php echo get_the_title();?>"
                     class="<?php echo esc_attr($class_image);?>">
            </a>
            <?php do_action('st_list_compare_button',get_the_ID(),get_post_type(get_the_ID())); ?>
            <?php echo st()->load_template( 'layouts/modern/common/star', '', [ 'star' => $hotel_star ] ); ?>
        </div>
        <div class="content-item">
            <h4 class="title" itemprop="name"><a href="<?php echo esc_url($url); ?>" class="st-link c-main"><?php echo get_the_title($post_translated) ?></a></h4>
            <?php
                if ( $address ) {
                    ?>
                    <div class="sub-title d-flex align-items-center" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress"><?php echo TravelHelper::getNewIcon('Ico_maps', '#666666', '15px', '15px', true); ?><span itemprop="streetAddress"><?php echo esc_html( $address ); ?></span></div>
                    <?php
                }
            ?>
            <div class="section-footer">
                <div class="reviews" itemprop="starRating" itemscope itemtype="https://schema.org/Rating">
                    <span class="rate" itemprop="ratingValue"><?php echo esc_attr( $review_rate ); ?>
                        /5 <?php echo TravelHelper::get_rate_review_text( $review_rate, $count_review ); ?>
                    </span>
                    <span class="summary">
                        <?php comments_number( __( 'No Review', 'traveler' ), __( '1 Review', 'traveler' ), get_comments_number() . ' ' . __( 'Reviews', 'traveler' ) ); ?>
                    </span>
                </div>
                <div class="price-wrapper d-flex align-items-center" itemprop="priceRange">
                    <?php echo TravelHelper::getNewIcon('thunder', '#ffab53', '10px', '16px'); ?>
                    <?php echo __('from', 'traveler'); ?> <span class="price"><?php echo TravelHelper::format_money( $price ) ?></span><span
                            class="unit"><?php echo __( '/night', 'traveler' ) ?></span>
                </div>
            </div>
        </div>
        
    </div>
</div>
