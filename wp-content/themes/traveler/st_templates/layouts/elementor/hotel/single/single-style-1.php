<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-11-2018
 * Time: 8:47 AM
 * Since: 1.0.0
 * Updated: 1.0.0
 */
wp_enqueue_script('single-hotel-detail');
while (have_posts()): the_post();
    $price = STHotel::get_price();
    $post_id = get_the_ID();
    $hotel_star = (int)get_post_meta($post_id, 'hotel_star', true);
    $address = get_post_meta($post_id, 'address', true);
    $review_rate = STReview::get_avg_rate();
    $count_review = get_comment_count($post_id)['approved'];
    $lat = get_post_meta($post_id, 'map_lat', true);
    $lng = get_post_meta($post_id, 'map_lng', true);
    $zoom = get_post_meta($post_id, 'map_zoom', true);
    $price = STHotel::get_price();
    $marker_icon = st()->get_option('st_hotel_icon_map_marker', '');
    ?>
    <div id="st-content-wrapper">
        <?php st_breadcrumbs_new() ?>
        <div class="container">
            <div class="st-hotel-content">
                <div class="hotel-target-book-mobile d-flex justify-content-between align-items-center">
                    <div class="price-wrapper">
                        <?php echo wp_kses(sprintf(__('from <span class="price">%s</span>', 'traveler'), TravelHelper::format_money($price)), ['span' => ['class' => []]]) ?>
                    </div>
                    <a href=""
                       class="btn btn-mpopup btn-green"><?php echo esc_html__('Check Availability', 'traveler') ?></a>
                </div>
            </div>
            <div class="st-service-header d-flex justify-content-between align-items-center">
                <div class="left">
                    <?php echo st()->load_template('layouts/elementor/common/star', '', ['star' => $hotel_star]); ?>
                    <h1 class="st-heading"><?php the_title(); ?></h1>
                    <div class="sub-heading">
                        <?php if ($address) {
                            echo TravelHelper::getNewIcon('Ico_maps', '#5E6D77', '16px', '16px');
                            echo esc_html($address);
                        }
                        ?>
                        <a href="javascript: void(0)" class="st-link font-medium" data-bs-toggle="modal"
                        data-bs-target="#st-modal-show-map"> <?php echo esc_html__('View on map', 'traveler') ?></a>
                        
                    </div>
                </div>
                <div class="right d-none d-sm-block">
                    <div class="review-score">
                        <div class="head d-flex justify-content-between align-items-center clearfix">
                            <div class="left">
                                <span class="head-rating"><?php echo TravelHelper::get_rate_review_text($review_rate, $count_review); ?></span>
                                <span class="text-rating"><?php comments_number(__('from 0 review', 'traveler'), __('from 1 review', 'traveler'), __('from % reviews', 'traveler')); ?></span>
                            </div>
                            <div class="score">
                                <?php echo esc_html($review_rate); ?><span>/5</span>
                            </div>
                        </div>
                        <div class="foot">
                            <?php echo esc_html__('100% of guests recommend', 'traveler') ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-9">
                    <?php echo st()->load_template('layouts/elementor/hotel/single/item/gallery','',['post_id' => $post_id]); ?>
                    <div class="st-hr"></div>
                    <?php echo st()->load_template('layouts/elementor/hotel/single/item/description'); ?>
                    <?php echo st()->load_template('layouts/elementor/hotel/single/item/attributes','',['post_type' => 'st_hotel']);?>
                    <?php echo st()->load_template('layouts/elementor/hotel/single/item/rules','',['post_id' => $post_id]); ?>
                    <div class="st-hr large"></div>
                    
                    <?php echo st()->load_template('layouts/elementor/hotel/single/item/list-room','',['post_id' => $post_id]); ?>
                    <?php echo st()->load_template('layouts/elementor/hotel/single/item/review','',['post_id' => $post_id]); ?>
                    <div class="stoped-scroll-section"></div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="widgets">
                        <div class="fixed-on-mobile" data-screen="992px">
                            <div class="close-icon hide">
                                <?php echo TravelHelper::getNewIcon('Ico_close'); ?>
                            </div>
                            <?php echo st()->load_template('layouts/elementor/hotel/single/item/form-book','',['price' => $price]); ?>

                            <?php echo st()->load_template('layouts/elementor/hotel/single/item/owner-info'); ?>

                            <?php echo st()->load_template('layouts/modern/common/single/information-contact'); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                echo st()->load_template('layouts/elementor/hotel/single/item/hotel-nearby');
            ?>
        </div>
        <!-- Modal Map Popup -->
        <div class="modal fade modal-map" id="st-modal-show-map" tabindex="-1" role="dialog"
                                aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"><?php the_title(); ?></h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <?php echo TravelHelper::getNewIcon('Ico_close'); ?></button>
                    </div>
                    <div class="modal-body">
                    <?php
                    $default = apply_filters('st_hotel_property_near_by_params', array(
                        'number'      => '12' ,
                        'range'       => '50' ,
                        'show_circle' => 'no' ,
                    ));
                    extract($default);
                    $hotel = new STHotel();
                    $location_center  = '[' . $lat . ',' . $lng . ']';
                    $map_lat_center = $lat;
                    $map_lng_center = $lng;
                    $map_icon = st()->get_option('st_hotel_icon_map_marker', '');
                    $map_icon = get_template_directory_uri() . '/v2/images/markers/ico_mapker_hotel.png';
                    if (empty($map_icon)){
                        $map_icon = get_template_directory_uri() . '/v2/images/markers/ico_mapker_hotel.png';
                    }

                    $data_map = array();
                    $stt  =  1;
                    global $post;
                    if (st()->get_option('st_show_hotel_nearby') == 'on') {
                        $data  = $hotel->get_near_by( get_the_ID() , $range , $number );
                        if(!empty( $data )) {
                            $stt  =  1;
                            foreach( $data as $post ) :
                                setup_postdata( $post );
                                $map_lat = get_post_meta( get_the_ID() , 'map_lat' , true );
                                $map_lng = get_post_meta( get_the_ID() , 'map_lng' , true );
                                if(!empty( $map_lat ) and !empty( $map_lng ) and is_numeric( $map_lat ) and is_numeric( $map_lng )) {
                                    $data_val = array(
                                        'id' => get_the_ID(),
                                        'post_id' => get_the_ID(),
                                        'name' => get_the_title(),
                                        'description' => "",
                                        'lat' => (float)$map_lat,
                                        'lng' => (float)$map_lng,
                                        'icon_mk' => $map_icon,
                                        'featured' => get_the_post_thumbnail_url(get_the_ID()),
                                        'url' => get_permalink(get_the_ID()),
                                    );
                                    $post_type                              = get_post_type();
                                    $data_map[$stt][ 'id' ]               = get_the_ID();
                                    $data_map[$stt][ 'name' ]             = get_the_title();
                                    $data_map[$stt][ 'post_type' ]        = $post_type;
                                    $data_map[$stt][ 'lat' ]              = $map_lat;
                                    $data_map[$stt][ 'lng' ]              = $map_lng;
                                    $data_map[$stt][ 'icon_mk' ]          = $map_icon;
                                    $data_map[$stt]['content_html'] = preg_replace('/^\s+|\n|\r|\s+$/m', '', st()->load_template('layouts/modern/hotel/elements/property',false,['data' => $data_val]));
                                    $stt++;
                                }
                            endforeach;
                            wp_reset_postdata();
                        }
                    }
                    $properties = $hotel->properties_near_by(get_the_ID(), $lat, $lng, $range);
                    if( !empty($properties) ){
                        foreach($properties as $key => $val){
                            $data_map[] = array(
                                'id' => get_the_ID(),
                                'name' => $val['name'],
                                'post_type' => 'st_hotel',
                                'lat' => (float)$val['lat'],
                                'lng' => (float)$val['lng'],
                                'icon_mk' => (empty($val['icon']))? 'http://maps.google.com/mapfiles/marker_black.png': $val['icon'],
                                'content_html' => preg_replace('/^\s+|\n|\r|\s+$/m', '', st()->load_template('layouts/modern/hotel/elements/property',false,['data' => $val])),

                            );
                        }
                    }

                    $data_map_origin = array();
                    $data_map_origin = array(
                        'id' => $post_id,
                        'post_id' => $post_id,
                        'name' => get_the_title(),
                        'description' => "",
                        'lat' => $lat,
                        'lng' => $lng,
                        'icon_mk' => $map_icon,
                        'featured' => get_the_post_thumbnail_url($post_id),
                    );
                    $data_map[] = array(
                        'id' => $post_id,
                        'name' => get_the_title(),
                        'post_type' => 'st_hotel',
                        'lat' => $lat,
                        'lng' => $lng,
                        'icon_mk' => $map_icon,
                        'content_html' => preg_replace('/^\s+|\n|\r|\s+$/m', '', st()->load_template('layouts/modern/hotel/elements/property',false,['data' => $data_map_origin])),

                    );

                    $data_map       = json_encode( $data_map , JSON_FORCE_OBJECT );
                    ?>
                    <?php $google_api_key = st()->get_option('st_googlemap_enabled');
                    if ($google_api_key === 'on') { ?>
                        <div class="st-map mt30">
                            <div class="google-map gmap3" id="list_map"
                                data-data_show='<?php echo str_ireplace(array("'"),'\"',$data_map) ;?>'
                                data-lat="<?php echo trim($lat) ?>"
                                data-lng="<?php echo trim($lng) ?>"
                                data-icon="<?php echo esc_url($marker_icon); ?>"
                                data-zoom="<?php echo (int)$zoom; ?>" data-disablecontrol="true"
                                data-showcustomcontrol="true"
                                data-style="normal">
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="st-map-box mt30">
                            <div class="google-map-mapbox" data-lat="<?php echo trim($lat) ?>"
                                data-data_show='<?php echo str_ireplace(array("'"),'\"',$data_map) ;?>'
                                    data-lng="<?php echo trim($lng) ?>"
                                    data-icon="<?php echo esc_url($marker_icon); ?>"
                                    data-zoom="<?php echo (int)$zoom; ?>" data-disablecontrol="true"
                                    data-showcustomcontrol="true"
                                    data-style="normal">
                                <div id="st-map">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal Map Popup -->
    </div>
    
<?php endwhile;
