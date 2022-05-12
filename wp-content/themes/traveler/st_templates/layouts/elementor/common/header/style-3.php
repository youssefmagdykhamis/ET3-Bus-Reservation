<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 12-11-2018
 * Time: 11:47 AM
 * Since: 1.0.0
 * Updated: 1.0.0
 */
?>
<header id="header" class="header-style-3">
    <?php
    $enable_topbar = st()->get_option('enable_topbar', 'on');
    if ($enable_topbar == 'on') {
        $hidden_topbar_in_mobile = st()->get_option('hidden_topbar_in_mobile', 'on');
        if ($hidden_topbar_in_mobile == 'off' || ($hidden_topbar_in_mobile == 'on' && !wp_is_mobile())) {
            ?>
            <div id="topbar">
                <?php
                $sort_topbar_menu = st()->get_option('sort_topbar_menu', false);
                if ($sort_topbar_menu) {
                    ?>
                    <div class="topbar-left">
                        <ul class="st-list socials">
                            <li>
                                <?php
                                foreach ($sort_topbar_menu as $key => $val) {
                                    $target = '';
                                    if (!empty($val['topbar_custom_link_target']) && $val['topbar_custom_link_target'] == 'on') {
                                        $target = '_blank';
                                    }
                                    $icon = esc_html($val['topbar_custom_link_icon']);
                                    if (!empty($val['topbar_item']) && $val['topbar_position'] == 'left' && isset($val['topbar_is_social']) && $val['topbar_is_social'] == 'on') {
                                        echo '<a href="' . esc_url($val['topbar_custom_link']) . '" target="' . esc_attr($target) . '"><i class="' . esc_attr($icon) . '"></i></a>';
                                    }
                                }
                                ?>
                            </li>
                        </ul>
                        <ul class="st-list topbar-items">
                            <?php
                            foreach ($sort_topbar_menu as $key => $val) {
                                if (!empty($val['topbar_item']) && $val['topbar_position'] == 'left' && (empty($val['topbar_is_social']) || $val['topbar_is_social'] == 'off')) {
                                    echo '<li class="d-none d-sm-none d-md-inline-block"><a href="' . esc_url($val['topbar_custom_link']) . '" target="' . esc_attr($target) . '">' . esc_html($val['topbar_custom_link_title']) . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                    <?php
                }
                ?>
                <div class="topbar-right">
                    <ul class="st-list topbar-items">
                        <?php
                        foreach ($sort_topbar_menu as $key => $val) {
                            if (!empty($val['topbar_item']) && $val['topbar_position'] == 'right') {
                                if ($val['topbar_item'] == 'login') {
                                    echo st()->load_template('layouts/elementor/common/header/topbar-items/login', '');
                                }
                                if ($val['topbar_item'] == 'currency') {
                                    echo st()->load_template('layouts/elementor/common/header/topbar-items/currency', '');
                                }
                                if ($val['topbar_item'] == 'language') {
                                    echo st()->load_template('layouts/elementor/common/header/topbar-items/language', '');
                                }
                                if ( $val[ 'topbar_item' ] == 'link' ) {
                                    $topbar_custom_class = isset( $val[ 'topbar_custom_class' ] ) ? $val[ 'topbar_custom_class' ] : '';?>
                                        <li class="d-none d-sm-none d-md-inline-block topbar-item link-item <?php echo esc_attr($topbar_custom_class);?>">
                                        <a href="<?php echo esc_url($val['topbar_custom_link']); ?>" class="login"><?php echo esc_html($val[ 'topbar_custom_link_title' ]); ?></a>
                                    </li>
                                <?php }
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <div class="header d-flex justify-content-between align-items-center">
        <a href="#" class="toggle-menu"><?php echo TravelHelper::getNewIcon('Ico_off_menu'); ?></a>
        <div class="header-left d-flex align-items-center">
            <?php
            $logo_url = st()->get_option('logo_new');
            $logo_mobile_url = st()->get_option('logo_mobile', $logo_url);
            if (empty($logo_mobile_url))
                $logo_mobile_url = $logo_url;
            ?>
            <a href="<?php echo home_url( '/' ) ?>" class="logo d-none d-sm-block d-md-block">
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo get_bloginfo( 'description' ); ?>">
            </a>
            <a href="<?php echo home_url( '/' ) ?>" class="logo  d-block d-sm-none d-md-none">
                <img src="<?php echo esc_url( $logo_mobile_url ); ?>" alt="<?php echo get_bloginfo( 'description' ); ?>">
            </a>
            <nav id="st-main-menu">
                <a href="" class="back-menu"><i class="fas fa-angle-left"></i></i></a>
                <?php
                if (has_nav_menu('primary')) {
                    if ( has_nav_menu( 'primary' ) ) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            "container" => "",
                            'items_wrap' => '<ul id="main-menu" class="%2$s main-menu">%3$s</ul>',
                            'depth' => 10,
                            'walker' => new st_menu_walker_v3(),
                        ));
                    }
                }
                ?>
            </nav>
        </div>
        <div class="header-right">
            <?php
            $sort_header_menu = st()->get_option('sort_header_menu', '');
            if (!empty($sort_header_menu) and is_array($sort_header_menu)) {
                ?>
                <ul class="st-list d-flex align-items-center">
                    <?php
                    foreach ($sort_header_menu as $key => $val) {
                        if (!empty($val['header_item'])) {
                            if ($val['header_item'] == 'login') {
                                echo st()->load_template('layouts/elementor/common/header/topbar-items/login', '', array('in_header' => true));
                            }
                            if ($val['header_item'] == 'currency') {
                                echo st()->load_template('layouts/elementor/common/header/topbar-items/currency', '');
                            }
                            if ($val['header_item'] == 'language') {
                                echo st()->load_template('layouts/elementor/common/header/topbar-items/language', '');
                            }
                            if ($val['header_item'] == 'link') {
                                $icon = '';
                                if (!empty($val['header_custom_link_icon'])) {
                                    $icon = esc_html($val['header_custom_link_icon']);
                                }
                                echo '<li class="st-header-link"><a href="' . esc_url($val['header_custom_link']) . '"> <i class="fa ' . esc_attr($icon) . ' mr5"></i>' . esc_html($val['header_custom_link_title']) . '</a></li>';
                            }
                            if ($val['header_item'] == 'shopping_cart') {
                                echo st()->load_template('layouts/elementor/common/header/topbar-items/cart', '');
                            }
                            if ($val['header_item'] == 'search') {
                                $search_header_onoff = st()->get_option('search_header_onoff', 'on');
                                if ($search_header_onoff == 'on'):
                                    echo st()->load_template('layouts/elementor/common/header/topbar-items/search', '');
                                endif;
                            }
                        }
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
        </div>
    </div>
</header>
