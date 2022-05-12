<?php
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!class_exists('ST_Testimonial_Element')) {
    class ST_Testimonial_Element extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'st_testimonial';
        }

        public function get_title()
        {
            return esc_html__('Testimonial', 'traveler');
        }

        public function get_icon()
        {
            return 'eicon-hotspot';
        }

        public function get_categories()
        {
            return ['st_elements'];
        }

        protected function register_controls()
        {
            $this->start_controls_section(
                'settings_section',
                [
                    'label' => esc_html__('Settings', 'traveler'),
                    'tab' => Controls_Manager::TAB_CONTENT
                ]
            );
            $this->add_control(
                'st_style_testimonial',
                [
                    'label' => esc_html__( 'Type', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' => [
                        'normal'  => esc_html__( 'Normal', 'traveler' ),
                        'slider' => esc_html__( 'Style slider 1', 'traveler' ),
                        'slider-2' => esc_html__( 'Style slider 2', 'traveler' ),
                    ],
                    'default' => 'normal',
                ]
            );            
            $this->add_control(
                'slides_per_view',
                [
                    'label' => esc_html__('Number Item in row ', 'traveler'),
                    'type' => 'select',
                    'label_block' => true,
                    'options' => [
                        '2'  => esc_html__( '2 items', 'traveler' ),
                        '3' => esc_html__( '3 items', 'traveler' ),
                        '4' => esc_html__( '4 items', 'traveler' ),
                    ],
                    'default' => '3',
                    'frontend_available' => true
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'st_avatar_testimonial',
                [
                    'label' => esc_html__( 'Avatar', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                ]
            );
            $repeater->add_control(
                'name_testimonial',
                [
                    'label' => esc_html__('Name', 'traveler'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );
            $repeater->add_control(
                'content_testimonial',
                [
                    'label' => esc_html__( 'Content', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::TEXTAREA,
                ]
            );
            $repeater->add_control(
                'st_star_testimonial',
                [
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'label' => esc_html__( 'Rate', 'plugin-name' ),
                    'min' => 0,
                    'max' => 5,
                    'step' => 1,
                    'default' => 0,
                ]
            );
            $this->add_control(
                'list_testimonial',
                [
                    'label' => esc_html__( 'List item', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ name_testimonial }}}',
                ]
            );
            $this->end_controls_section();

            //Addvance Slider
            $this->start_controls_section(
                'settings_slider_section',
                [
                    'label' => esc_html__('Settings Slider', 'traveler'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                    'condition' => [
                        'st_style_testimonial' => ['slider','slider-2']
                    ]
                ]
            );
            $this->add_control(
                'pagination',
                [
                    'label' => esc_html__('Pagination', 'traveler'),
                    'type' => 'select',
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'options' => [
                        'on'  => esc_html__( 'On', 'traveler' ),
                        'off' => esc_html__( 'Off', 'traveler' ),
                    ],
                    'default' => 'off',
                ]
            );
            $this->add_control(
                'navigation',
                [
                    'label' => esc_html__('Navigation', 'traveler'),
                    'type' => 'select',
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'options' => [
                        'on'  => esc_html__( 'On', 'traveler' ),
                        'off' => esc_html__( 'Off', 'traveler' ),
                    ],
                    'default' => 'off',
                ]
            );
            $this->add_control(
                'effect_style',
                [
                    'label' => esc_html__('Style Effect', 'traveler'),
                    'type' => 'select',
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'options' => [
                        'creative'  => esc_html__( 'Creative', 'traveler' ),
                        'coverflow' => esc_html__( 'Coverflow', 'traveler' ),
                        'cards' => esc_html__( 'Cards', 'traveler' ),
                    ],
                    'default' => 'creative',
                ]
            );

            $this->add_control(
                'auto_play',
                [
                    'label' => esc_html__('Auto play', 'traveler'),
                    'type' => 'select',
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'options' => [
                        'on'  => esc_html__( 'On', 'traveler' ),
                        'off' => esc_html__( 'Off', 'traveler' ),
                    ],
                    'default' => 'off',
                ]
            );
            $this->add_control(
                'delay',
                [
                    'label' => esc_html__('Delay auto play', 'traveler'),
                    'type' => Controls_Manager::NUMBER,
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'default' => '3000',
                    'condition' => [
                        'auto_play' => 'on'
                    ]
                ]
            );
            $this->add_control(
                'loop',
                [
                    'label' => esc_html__('Loop slider', 'traveler'),
                    'type' => 'select',
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'options' => [
                        'true'  => esc_html__( 'On', 'traveler' ),
                        'false' => esc_html__( 'Off', 'traveler' ),
                    ],
                    'default' => 'false',
                    'condition' => [
                        'auto_play!' => 'on'
                    ]
                ]
            );
            
            

            $this->end_controls_section();

            //Style
            $this->start_controls_section(
                'style_section',
                [
                    'label' => esc_html__('Style', 'traveler'),
                    'tab' => Controls_Manager::TAB_STYLE,
                    'default' => 'single',
                ]
            );
            $this->add_responsive_control(
                'space_bottom',
                [
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'label' => esc_html__('Space bottom', 'traveler'),
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
                    'devices' => [ 'desktop', 'tablet', 'mobile' ],
                    'desktop_default' => [
                        'size' => 30,
                        'unit' => 'px',
                    ],
                    'tablet_default' => [
                        'size' => 20,
                        'unit' => 'px',
                    ],
                    'mobile_default' => [
                        'size' => 10,
                        'unit' => 'px',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .item-testimonial' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    ],
                    'condition' => [
                        'st_style_testimonial' => 'normal'
                    ]
                ]
            );
            $this->add_responsive_control(
                'item_padding',
                [
                    'type' => \Elementor\Controls_Manager::DIMENSIONS,
                    'label' => esc_html__( 'Item padding', 'traveler' ),
                    'size_units' => [ 'px', 'em', '%' ],
                    'default' => [
                        'top' => '20',
                        'right' => '20',
                        'bottom' => '20',
                        'left' => '20',
                        'unit' => 'px',
                        'isLinked' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .item-testimonial .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
           
            $this->end_controls_section();
            
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            $settings = array_merge(array('_element' => $this), $settings);
            ST_Elementor::view('testimonial.template', $settings);
        }
    }
}
