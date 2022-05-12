<?php
global $post;

$pickup_date = STInput::get('pick-up-date', date(TravelHelper::getDateFormat()));
$dropoff_date = STInput::get('drop-off-date', date(TravelHelper::getDateFormat(), strtotime("+ 1 day")));

$pickup_date = TravelHelper::convertDateFormat($pickup_date);
$dropoff_date = TravelHelper::convertDateFormat($dropoff_date);

$pick_up_time = STInput::get('pick-up-time', '12:00 PM');
$drop_off_time = STInput::get('drop-off-time', '12:00 PM');

$info_price = STCars::get_info_price(get_the_ID(), strtotime($pickup_date), strtotime($dropoff_date));
$cars_price = $info_price['price'];
$count_sale = $info_price['discount'];
$price_origin = $info_price['price_origin'];
$list_price = $info_price['list_price'];
$url = st_get_link_with_search(get_permalink(), array('location_id','location_id_drop_off','location_name','drop-off','pick-up-date', 'drop-off-date', 'pick-up-time', 'drop-off-time'), $_REQUEST);
?>
<div class="item item-rental item-service item-service-inner item-car st-border-radius" itemscope itemtype="https://schema.org/RentalCarReservation">
    <div class="row align-content-around flex-wrap item-service-wrapper has-matchHeight">
        <div class="col-sm-4">
            <div class="featured-image">
                <?php if (!empty($info_price['discount']) and $info_price['discount'] > 0 and $info_price['price'] > 0) { ?>
                    <?php echo STFeatured::get_sale($info_price['discount']); ?>
                <?php } ?>
                <?php if (is_user_logged_in()) { ?>
                    <?php $data = STUser_f::get_icon_wishlist(); ?>
                    <div class="service-add-wishlist login <?php echo ($data['status']) ? 'added' : ''; ?>"
                         data-id="<?php echo get_the_ID(); ?>" data-type="<?php echo get_post_type(get_the_ID()); ?>"
                         title="<?php echo ($data['status']) ? __('Remove from wishlist', 'traveler') : __('Add to wishlist', 'traveler'); ?>">
                        <i class="fa fa-heart"></i>
                        <div class="lds-dual-ring"></div>
                    </div>
                <?php } else { ?>
                    <a href="" class="login" data-toggle="modal" data-target="#st-login-form">
                        <div class="service-add-wishlist" title="<?php echo __('Add to wishlist', 'traveler'); ?>">
                            <i class="fa fa-heart"></i>
                            <div class="lds-dual-ring"></div>
                        </div>
                    </a>
                <?php } ?>
                <div class="service-tag bestseller">
                    <?php echo STFeatured::get_featured(); ?>
                </div>
                <a href="<?php echo esc_url($url); ?>">
                    <?php
                    if (has_post_thumbnail()) {
                        the_post_thumbnail(array(760, 460), array('alt' => TravelHelper::get_alt_image(), 'class' => 'img-responsive', 'itemprop'=>'image'));
                    } else {
                        echo '<img src="' . get_template_directory_uri() . '/img/no-image.png' . '" alt="Default Thumbnail" class="img-responsive" />';
                    }
                    ?>
                </a>
                <?php do_action('st_list_compare_button',get_the_ID(),get_post_type(get_the_ID())); ?>
                <div class="d-block d-sm-none">
                    <?php echo st_get_avatar_in_list_service(get_the_ID(), 70) ?>
                </div>
                
            </div>
        </div>
        <div class="col-sm-5 item-content">
            <div class="h-100 d-flex align-items-center">
                <div class="item-content-w">
                    <?php
                    $category = get_the_terms(get_the_ID(), 'st_category_cars');
                    if (!is_wp_error($category) && is_array($category)) {
                        $category = array_shift($category);
                        echo '<div class="car-type">' . esc_html($category->name) . '</div>';
                    }
                    ?>
                    <h4 class="service-title" itemprop="name"><a href="<?php echo esc_url($url); ?>"><?php echo get_the_title(); ?></a></h4>
                    <div class="service-review d-flex d-sm-none d-md-none">
                        <ul class="icon-group  text-color booking-item-rating-stars">
                            <?php
                            $avg = STReview::get_avg_rate();
                            echo TravelHelper::rate_to_string($avg);
                            ?>
                        </ul>
                        <?php
                        $count_review = STReview::count_comment(get_the_ID());
                        ?>
                        <span class="review"><?php echo esc_html($count_review) . ' ' . _n(esc_html__('Review', 'traveler'), esc_html__('Reviews', 'traveler'), $count_review); ?></span>
                    </div>
                    <div class="car-equipments amenities clearfix">
                        <?php
                        $pasenger = (int)get_post_meta(get_the_ID(), 'passengers', true);
                        $auto_transmission = get_post_meta(get_the_ID(), 'auto_transmission', true);
                        $baggage = (int)get_post_meta(get_the_ID(), 'baggage', true);
                        $door = (int)get_post_meta(get_the_ID(), 'door', true);
                        ?>
                        <span class="amenity amenity-car" data-bs-toggle="tooltip" title="<?php echo esc_attr__('Passenger', 'traveler') ?>">
                            <span class="ico"><?php echo TravelHelper::getNewIcon('ico_regular_1', '#1A2B50', '22px', '22px') ?></span>
                            <span class="text"><?php echo esc_attr($pasenger); ?></span>
                        </span>
                        <span class="amenity amenity-car" data-bs-toggle="tooltip" title="<?php echo esc_attr__('Gear Shift', 'traveler') ?>">
                            <span class="ico"><?php echo TravelHelper::getNewIcon('ico_gear_shift', '#1A2B50', '22px', '22px') ?></span>
                            <span class="text"><?php if ($auto_transmission == 'on') echo esc_html__('Auto', 'traveler'); else echo esc_html__('Not Auto', 'traveler') ?></span>
                        </span>
                        <span class="amenity amenity-car" data-bs-toggle="tooltip" title="<?php echo esc_attr__('Baggage', 'traveler') ?>">
                            <span class="ico"><?php echo TravelHelper::getNewIcon('ico_suite_1', '#1A2B50', '22px', '22px') ?></span>
                            <span class="text"><?php echo esc_attr($baggage); ?></span>
                        </span>
                        <span class="amenity amenity-car">
                            <span class="ico" data-bs-toggle="tooltip" title="<?php echo esc_attr__('Door', 'traveler') ?>"><?php echo TravelHelper::getNewIcon('ico_doors_1', '#1A2B50', '22px', '22px') ?></span>
                            <span class="text"><?php echo esc_attr($door); ?></span>
                        </span>
                    </div>
                    <?php
                    $show_avatar = st()->get_option('avatar_in_list_service', 'off');
                    if ($show_avatar == 'on') {
                        ?>
                        <div class="service-author">
                            <?php echo st_get_avatar_in_list_service(get_the_ID(), 70) ?>
                            <p class="name">
                                <?php
                                $post_author_id = get_post_field('post_author', get_the_ID());
                                echo trim(TravelHelper::get_username($post_author_id));
                                ?>
                            </p>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
        </div>
        <div class="col-sm-3 section-footer">
            <div class="h-100 footer-flex d-flex align-content-between flex-wrap">
                <div class="reviews d-block d-sm-none d-md-none">
                    <?php
                    $count_review = STReview::count_comment(get_the_ID());
                    $avg = STReview::get_avg_rate();
                    ?>
                    <span class="rating"><?php echo esc_html($avg); ?>/5 <?php echo TravelHelper::get_rate_review_text($avg, $count_review); ?></span>
                    <span class="st-dot"></span>
                    <span class="review"><?php echo esc_html($count_review) . ' ' . _n(esc_html__('Review', 'traveler'), esc_html__('Reviews', 'traveler'), $count_review); ?></span>
                </div>
                <div class="price-wrapper">
                    <span>
                        <?php echo TravelHelper::getNewIcon('thunder', '#ffab53', '10px', '16px'); ?>
                    </span>
                    <span class="price">
                        <?php
                        echo TravelHelper::format_money($cars_price);
                        ?>
                    </span>
                    <span class="unit">/<?php echo strtolower(STCars::get_price_unit('label')) ?></span>
                    <div class="show-detail">
                        <a href="<?php echo esc_url($url) ?>"
                        class="btn btn-primary btn-view-more"><?php echo __('VIEW CAR', 'traveler'); ?></a>

                        <?php if (!empty($info_price['discount']) and $info_price['discount'] > 0 and $info_price['price'] > 0) { ?>
                            <?php echo STFeatured::get_sale($info_price['discount']); ?>
                        <?php } ?>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>
