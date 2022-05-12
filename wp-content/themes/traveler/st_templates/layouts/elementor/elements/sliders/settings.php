<?php
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!class_exists('ST_Sliders_Element')) {
    class ST_Sliders_Element extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'st_sliders';
        }

        public function get_title()
        {
            return esc_html__('Sliders', 'traveler');
        }

        public function get_icon()
        {
            return 'eicon-hotspot';
        }

        public function get_categories()
        {
            return ['st_elements'];
        }

        public function get_script_depends()
        {
            return ['swiper'];
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
                'st_sliders',
                [
                    'label' => esc_html__( 'Add Images Slider', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::GALLERY,
                    'default' => [],
                ]
            );

            $this->end_controls_section();

            $this->start_controls_section(
                'settings_slider_section',
                [
                    'label' => esc_html__('Settings Slider', 'traveler'),
                    'tab' => Controls_Manager::TAB_CONTENT,
                ]
            );
            $this->add_control(
                'center_slider',
                [
                    'label' => esc_html__('Center', 'traveler'),
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
                'width_slider_center',
                [
                    'label' => esc_html__('Set width slider item center', 'traveler'),
                    'type' => \Elementor\Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 100,
                            'max' => 500,
                            'step' => 5,
                        ],
                        '%' => [
                            'min' => 50,
                            'max' => 100,
                        ],
                    ],
                    'default' => [
                        'unit' => '%',
                        'size' => 75,
                    ],
                    'condition' => [
                        'center_slider' => 'on'
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .st-sliders .swiper-slide' => 'width: {{SIZE}}{{UNIT}};',
                    ],
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
            
            $this->add_control(
                'slides_per_view',
                [
                    'label' => esc_html__('Slides PerView in content', 'traveler'),
                    'type' => Controls_Manager::NUMBER,
                    'description' => esc_html__('See the Swiper API documentation https://swiperjs.com/swiper-api', 'traveler'),
                    'label_block' => true,
                    'default' => '1',
                    'frontend_available' => true,
                    'condition' => [
                        'center_slider!' => 'on'
                    ],
                ]
            );

            $this->end_controls_section();
            
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $settings = array_merge(array('_element' => $this), $settings);
            ST_Elementor::view('sliders.template', $settings);
        }
    }
}
