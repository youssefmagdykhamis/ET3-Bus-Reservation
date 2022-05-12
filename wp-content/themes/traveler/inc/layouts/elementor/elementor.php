<?php
class ST_Elementor{
    private static $instance = null;
    public function __construct()
    {
        add_action('elementor/elements/categories_registered', [$this, '_register_elementor_categories']);
        add_action('elementor/widgets/widgets_registered', [$this, '_register_element']);
        add_action('elementor/controls/register', [$this, '_register_controls']);
        add_action('elementor/editor/after_enqueue_styles', array($this, '_enqueue_styles'));
        add_action('wp_ajax_st_select2_ajax', [$this, '_select2_ajax']);

        //setting option admin
        add_filter('st_hotel_alone_active',[$this,'st_hotel_alone_active_func'],1);
        add_filter('hotel_alone_room_layout',[$this,'hotel_alone_room_layout_func'],1);

        //Hook remove check single hotel alone for single layout
        add_filter('elementor_room_single_layout',[$this,'elementor_room_single_layout_func'],1);
    }

    public function elementor_room_single_layout_func($arr_settings){
        return '';
    }
    public function hotel_alone_room_layout_func($arr_settings){
        return 'hotel_alone_room_layout:is(off)';
    }
    public function st_hotel_alone_active_func($arr_settings){
        if(check_using_elementor()){
            return null;
        }
        return $arr_settings;
    }

    public function _select2_ajax()
    {
        check_ajax_referer('security', 'security');
        $callback = !empty($_GET['callback']) ? $_GET['callback'] : '';
        if (empty($callback)) {
            wp_send_json([
                'items' => []
            ]);
        }
        $callback = explode(':', $callback);
        if (method_exists($callback[0], 'get_inst')) {
            $func = $callback[1];
            $callback[0]::get_inst()->$func();
        } else {
            $object = new $callback[0];
            $object->$callback[1];
        }
    }
    public function _enqueue_styles()
    {
        wp_localize_script('jquery', 'st_elementor_params', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('security')
        ]);
    }
    public function get_post_ajax()
    {
        $string = !empty($_GET['s']) ? $_GET['s'] : '';
        $post_type = !empty($_GET['post_type']) ? $_GET['post_type'] : 'post';
        $args = [
            'post_type' => $post_type,
            'posts_per_page' => -1,
            's' => $string,
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC'
        ];
        $items = [];
        $query_search = new WP_Query($args);
        if ($query_search->have_posts()) {
            while ($query_search->have_posts()): $query_search->the_post();
                $items[] = [
                    'id' => get_the_ID(),
                    'text' => get_the_title()
                ];
            endwhile;
            wp_reset_postdata();
        }

        wp_send_json([
            'results' => $items
        ]);
    }

    public function _register_controls($controls_manager)
    {
        include_once ST_TRAVELER_DIR . '/inc/layouts/elementor/select2-ajax-control.php';
        $controls_manager->register( new \ST_Select2_Ajax_Control() );
    }

    public function _register_elementor_categories($elements_manager)
    {
        $categories = [
            'st_elements' => [
                'icon' => 'fa fa-plug',
                'title' => esc_html__('Traveler Elements', 'wooler')
            ],
        ];
        if(is_array($categories)){
            foreach ($categories as $key => $category) {
                $elements_manager->add_category($key, $category);
            }
        }
        

        return $elements_manager;
    }

    public function _register_element($manager, $folder = '')
    {
        $list_element = [
            'banner-form',
            'list-service',
            'destination',
            'table-pricing',
            'personal-infor',
            'sliders',
            'currency',
            'testimonial',
        ];
        $elements = apply_filters( 'st-list-element-widget', $list_element );
        $url_file_path_elementor = get_parent_theme_file_path();
        foreach ($elements as $element_folder_name) {
            $folder_path = $url_file_path_elementor . '/st_templates/layouts/elementor/elements/' . $element_folder_name;
            if (is_dir($folder_path)) {
                $settings_file = $folder_path . '/settings.php';
                $custom_file = trailingslashit(get_template_directory()) . '/st_templates/layouts/elementor/elements/' . $element_folder_name . '/settings.php';
                if (is_file($settings_file)) {
                    if (is_file($custom_file)) {
                        require($custom_file);
                    } else {
                        require($settings_file);
                    }

                    //register style css element
                    $custom_file_css = trailingslashit(get_template_directory()) . '/st_templates/layouts/elementor/elements/' . $element_folder_name . '/assets/style.min.css';
                    if (is_file($custom_file_css)) {
                        $custom_file_css_url = trailingslashit(get_template_directory_uri()) . '/st_templates/layouts/elementor/elements/' . $element_folder_name . '/assets/style.min.css';
                        wp_register_style('st-'.$element_folder_name, $custom_file_css_url);
                    }
                    //register script js element
                    $custom_file_js = trailingslashit(get_template_directory()) . '/st_templates/layouts/elementor/elements/' . $element_folder_name . '/assets/script.js';
                    if (is_file($custom_file_js)) {
                        $custom_file_js_url = trailingslashit(get_template_directory_uri()) . '/st_templates/layouts/elementor/elements/' . $element_folder_name . '/assets/script.js';
                        wp_register_script('st-'.$element_folder_name, $custom_file_js_url);
                    }
                    $name = 'ST_' . ucwords(str_replace('-', '_', $element_folder_name), '_') . '_Element';
                    if (class_exists($name)) {
                        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new $name());
                    }
                }
            }
        }
    }
    public static function view($name = '', $params = null, $return = false)
    {
        $name = str_replace('.', '/', $name);
        $file = locate_template('st_templates/layouts/elementor/elements/' . $name . '.php');
        if (is_file($file)) {
            if (!empty($params) && is_array($params)) {
                extract($params);
            }
            ob_start();
            require($file);
            $buffer = ob_get_clean();
            if ($return) {
                return $buffer;
            } else {
                echo  $buffer;
            }
        } else {
            die('Unable to load the requested file: st_templates/layouts/elementor/elements/' . $name . '.php');
        }
    }
    public static function get_title_service($post_type_name){
        $string="";
        switch ($post_type_name){
            case 'st_hotel': $string = esc_html__('Hotel', 'traveler');break;
            case 'st_tours': $string = esc_html__('Tour', 'traveler');break;
            case 'st_activity': $string = esc_html__('Activity', 'traveler');break;
            case 'st_rental': $string = esc_html__('Rental', 'traveler');break;
            case 'st_cars': $string = esc_html__('Car', 'traveler');break;
            case 'st_cartransfer': $string = esc_html__('Transfercar', 'traveler');break;
        }
        return $string;
    }

    public static function listServiceSelection(){
        $option       = get_option( st_options_id() );
        $array_serrvice = ['st_hotel' , 'st_tours' , 'st_activity' , 'st_rental' , 'st_cars' , 'st_flight'];
        $disable_list = isset( $option[ 'list_disabled_feature' ] ) ? $option[ 'list_disabled_feature' ] : [];
        $array_enable_service = array_diff($array_serrvice, $disable_list);
        return $array_enable_service;
    }

    public static function listSerrviceSelectionName($item_exclude=array()){
        $option       = get_option( st_options_id() );
        $array_serrvice = apply_filters('st_services_core',[ 'st_tours' , 'st_hotel' ,'st_activity' , 'st_rental' , 'st_cars']);
        $array_serrvice = array_diff($array_serrvice,$item_exclude);
        $disable_list = isset( $option[ 'list_disabled_feature' ] ) ? $option[ 'list_disabled_feature' ] : [];
        $array_enable_service = array_diff($array_serrvice, $disable_list);
        if(!in_array('st_cartransfer',$item_exclude)){
            if(in_array('st_cars',$array_enable_service)){
                $array_enable_service[] = 'st_cartransfer';
            }
        }
        $array_list = [];
        foreach($array_enable_service as $service){
            $array_list[$service] = self::get_title_service($service);
        }
        return $array_list;
    }
    public static function st_explode(string $separator = ' ', string $string = ''): array
    {
        if (empty($string)) {
            return [];
        }
        
        return explode($separator, $string);
    }

    public static function listCategoryByTaxnomy($slug_taxonomy){
        $list_term =[];
        $get_terms = get_terms(
            [
                'taxonomy'   => $slug_taxonomy,
                'hide_empty' => false
            ]
        );
        $list_term['0:'.$slug_taxonomy] =  __('None','traveler');
        if ( $get_terms && !is_wp_error($get_terms)) {
            foreach ( $get_terms as $term ) {
                $list_term[$term->term_id.':'.$slug_taxonomy] = $term->name;
            }
        }
        
        
        return $list_term;
    }

    public static function st_explode_select2(string $string = '', int $limit = 0, $return = 'both'): array
    {
        if (empty($string)) {
            return [];
        }
        
        if ($limit < 0) {
            $limit = 0;
        }
        
        $listItem = self::st_explode(';;', $string);
        
        if (empty($listItem)) {
            return [];
        }
        
        $new_arr = [];
        $sizeListItem = count($listItem);
        if ($limit > $sizeListItem || $limit == 0) {
            $limit = $sizeListItem;
        }
        
        for ($i = 0; $i < $limit; $i++) {
            $tmp = self::st_explode('::', $listItem[$i]);
            if (count($tmp) == 2) {
                $new_arr[$tmp[0]] = $tmp[1];
            }
        }
        if (!empty($new_arr)) {
            if ($return == 'key') {
                return array_keys($new_arr);
            } elseif ($return == 'value') {
                return array_values($new_arr);
            }
        }
        
        return $new_arr;
    }

    public static function get_inst()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self();
        }

        return $instance;
    }
}
ST_Elementor::get_inst();