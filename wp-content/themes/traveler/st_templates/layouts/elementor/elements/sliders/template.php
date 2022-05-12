<?php 
    if(!empty($st_sliders)){ 
        $attrs = [
            'data-effect' => [
                esc_attr($effect_style)
            ],
            'data-slides-per-view' => [
                esc_attr($slides_per_view)
            ],
            'data-pagination' => [
                esc_attr($pagination)
            ],
            'data-navigation' => [
                esc_attr($navigation)
            ],
            'data-auto-play' => [
                esc_attr($auto_play)
            ],
            'data-loop' => [
                esc_attr($loop)
            ],
            'data-delay' => [
                esc_attr($delay)
            ],
            'data-center-slider' => [
                esc_attr($center_slider)
            ]
        ];
        ?>
        <div class="st-sliders" <?php echo st_render_html_attributes($attrs);?>>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <?php 
                        foreach($st_sliders as $slider){ 
                            ?>
                            <div class="swiper-slide">
                                <img class="swiper-lazy" src="<?php echo esc_url($slider['url']);?>" alt="<?php echo esc_attr(get_bloginfo( 'name' ));?>">
                            </div>
                        <?php }
                    ?>
                </div>
                <?php
                $html = '';
                if($pagination == 'on'){
                    $html .= '<div class="swiper-pagination"></div>';
                }
                if($navigation == 'on'){
                    $html .= '<div class="st-button-prev"><span></span></div><div class="st-button-next"><span></span></div>';
                } ?>
                <?php 
                    echo balanceTags($html);
                ?>
            </div>
            
        </div>
    <?php }
?>
