<?php

    if(!class_exists('st_widget_search_rental')){

        class st_widget_search_rental extends WP_Widget{



            public $cache_key='st_widget_search_rental';



            public function __construct() {

                $widget_ops = array('classname' => 'st_widget_search_rental', 'description' => __( "rental Filter Box",'traveler') );

                parent::__construct('st_widget_search_rental', __('ST Rental Filter Box','traveler'), $widget_ops);

                $this->alt_option_name = $this->cache_key;



                add_action( 'save_post', array($this, 'flush_widget_cache') );

                add_action( 'deleted_post', array($this, 'flush_widget_cache') );

                add_action( 'switch_theme', array($this, 'flush_widget_cache') );



                add_action('admin_enqueue_scripts',array($this,'add_scripts'));

            }



            function add_scripts()

            {





                $screen=get_current_screen();



                if($screen->base=='widgets'){

                    wp_enqueue_style('jquery-ui',get_template_directory_uri().'/css/admin/jquery-ui.min.css');

                    wp_enqueue_script('search-rental',get_template_directory_uri().'/js/admin/widgets/search-rental.js',array('jquery','jquery-ui-sortable'),null,true);

//            wp_enqueue_style('search-rental',get_template_directory_uri().'/css/admin/widgets/search-rental.css',array('jquery-ui'));



                    wp_localize_script('jquery','st_search_rental',array(

                        'default_item'=>$this->default_item()

                    ));

                }



            }



            public function widget($args, $instance) {





                $cache = array();

                if ( ! $this->is_preview() ) {

                    $cache = wp_cache_get( $this->cache_key, 'widget' );

                }



                if ( ! is_array( $cache ) ) {

                    $cache = array();

                }



                if ( ! isset( $args['widget_id'] ) ) {

                    $args['widget_id'] = $this->id;

                }



                if ( isset( $cache[ $args['widget_id'] ] ) ) {

                    echo balanceTags($cache[ $args['widget_id'] ]);

                    return;

                }



                ob_start();



                $default=array(

                    'title'=>__('Filter By:', 'traveler'),

                    'show_attribute'=>'',

                    'st_search_fields'=>'',

                    'style'         =>'dark'

                );



                $instance=wp_parse_args($instance,$default);





                echo st()->load_template('rental/filter',null,array(

                    'instance'=>$instance

                ));





                if ( ! $this->is_preview() ) {

                    $cache[ $args['widget_id'] ] = ob_get_flush();

                    wp_cache_set( $this->cache_key, $cache, 'widget' );

                } else {

                    ob_end_flush();

                }

            }



            public function update( $new_instance, $old_instance ) {

                $instance = $old_instance;

                $instance['title'] = strip_tags($new_instance['title']);



                $default=array(

                    'title'=>__('Filter By:', 'traveler'),

                    'show_attribute'=>'',

                    'st_search_fields'=>'',

                    'style'         =>'dark'

                );



                $new_instance=wp_parse_args($new_instance,$default);



                $instance=$new_instance;





                $this->flush_widget_cache();



                $alloptions = wp_cache_get( 'alloptions', 'options' );

                if ( isset($alloptions[$this->cache_key]) )

                    delete_option($this->cache_key);



                return $instance;

            }



            public function flush_widget_cache() {

                wp_cache_delete($this->cache_key, 'widget');

            }

            function default_item($key=0,$old=array()){



                $default=array(

                    'title'=>'',

                    'field'=>'',

                    'taxonomy'=>'',

                    'order'   =>'',

                    'style'         =>'dark'

                );



                extract(wp_parse_args($old,$default));







                $taxonomy_html='<select name="taxonomy['.$key.']" class="widefat field_taxonomy">';



                $tax=get_object_taxonomies('st_rental','OBJECTS');

                if(!empty($tax)){

                    foreach($tax as $key2=>$value2){

                        $taxonomy_html.="<option ".selected($key2,$taxonomy,false)." value='{$key2}'>{$value2->label}</option>";

                    }

                }



                $taxonomy_html.='</select>';







                $fields='<select name="field['.$key.']" class="widefat field_name">

                        <option '.selected('price',$field,false).' value="price">'.__('Price','traveler').'</option>

                        <option '.selected('rate',$field,false).' value="rate">'.__('Rate','traveler').'</option>

                        <option '.selected('taxonomy',$field,false).' value="taxonomy">'.__('Taxonomy','traveler').'</option>

                    </select>';



                return '<li class="ui-state-default">

                <p><label>'.__('Title','traveler').':</label><input class="widefat field_title" name="title['.$key.']" type="text" value="'.$title.'"></p>

                <input type="hidden" class="field_order" name="order['.$key.']" value="'.$key.'">

                <p><label>'.__('Field','traveler').':</label>'.$fields.'</p>

                <p class=""><label>'.__('Taxonomy','traveler').':</label>'.$taxonomy_html.'</p>

                <p class="field_tax_wrap"><a href="#" class="button st_delete_field" onclick="return false">'.__('Delete','traveler').'</a></p>

                </li>';

            }

            public function form( $instance ) {



                $default=array(

                    'title'=>__('Filter By:', 'traveler'),

                    'show_attribute'=>'',

                    'st_search_fields'=>'',

                    'style'         =>'dark'

                );



                extract($instance=wp_parse_args($instance,$default));





                ?>

                <p><label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php _e( 'Title:','traveler' ); ?></label>

                    <input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_html($title); ?>" /></p>

                <p><label for="<?php echo esc_attr($this->get_field_id( 'style' )); ?>"><?php _e( 'Style:' ,'traveler' ); ?></label>

                    <select name="<?php echo esc_attr($this->get_field_name( 'style' )); ?>">

                        <option value="dark"><?php _e('Dark','traveler')?></option>

                        <option <?php  selected('light',$style)?> value="light"><?php _e('Light','traveler')?></option>

                    </select>

                </p>



                <div class="st-search-fields">

                    <div class="fields-form" onsubmit="return false">

                        <ul class="fields-wrap">

                            <?php $list=json_decode($st_search_fields);



                                if(!empty($list) and is_array($list)){

                                    foreach($list as $key=>$value){

                                        echo balanceTags($this->default_item($key,$value));

                                    }

                                }

                            ?>

                        </ul>

                    </div>

                    <textarea name="<?php echo esc_attr($this->get_field_name( 'st_search_fields' )); ?>" class="st_search_fields_value"><?php echo balanceTags($st_search_fields)?></textarea>

                    <a href="#" class="button st_add_rental_field" onclick="return false;"><?php _e('Add New','traveler')?></a>

                    <a href="#" class="button st_save_rental_fields" onclick="return false;"><?php _e('Save List','traveler')?></a>

                </div>

            <?php

            }

        }





        function st_search_rental_widget_register() {

            if(st_check_service_available('st_rental'))

            register_widget( 'st_widget_search_rental' );

        }



        add_action( 'widgets_init', 'st_search_rental_widget_register' );

    }

