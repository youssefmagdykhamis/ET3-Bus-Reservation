<?php
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!class_exists('ST_Table_Pricing_Element')) {
    class ST_Table_Pricing_Element extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'st_table_pricing';
        }

        public function get_title()
        {
            return esc_html__('Table Pricing', 'traveler');
        }

        public function get_icon()
        {
            return 'eicon-hotspot';
        }

        public function get_categories()
        {
            return ['st_elements'];
        }

        public function st_get_packpage() {
            $cls_packages = STAdminPackages::get_inst();
            $packages = $cls_packages->get_packages();
            $arr_package = array();
            foreach ($packages as $key => $value) {
                $arr_package[$value->id] = $value->package_name;
            }
            $arr_package['no'] =  __('Setting', 'traveler');
            return $arr_package;
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
                'id_package',
                [
                    'label' => esc_html__('Member Package', 'traveler'),
                    'description' => esc_html__('Choose member package', 'traveler'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' => $this->st_get_packpage(),
                    'default' => 'no'
                ]
            );
            $this->add_control(
                'title_table',
                [
                    'label' => esc_html__('Title table', 'traveler'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'condition' => [
                        'id_package' => 'no'
                    ]
                ]
            );
            $this->add_control(
                'st_images_icon',
                [
                    'label' => esc_html__( 'Icon image', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                    'default' => [
                        'url' => \Elementor\Utils::get_placeholder_image_src(),
                    ],
                ]
            );
            $this->add_control(
                'sale_member',
                [
                    'label' => esc_html__('Enter number sale', 'traveler'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'frontend_available' => true,
                    'condition' => [
                        'id_package' => 'no'
                    ]
                ]
            );

            $repeater = new \Elementor\Repeater();

            $repeater->add_control(
                'check', [
                    'label' => esc_html__( 'Support', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'options' => [
                        'check'  => esc_html__( 'Check', 'traveler' ),
                        'no' => esc_html__( 'No check', 'traveler' ),
                    ],
                    'default' => 'check',
                ]
            );
            $repeater->add_control(
                'title_items', [
                    'label' => esc_html__( 'Title item', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                ]
            );

            $this->add_control(
                'list_support',
                [
                    'label' => esc_html__( 'List item', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'title_field' => '{{{ title_items }}}',
                ]
            );

            $this->add_control(
                'text_button',
                [
                    'label' => esc_html__('Text button', 'traveler'),
                    'description' => esc_html__('Choose Text button', 'traveler'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'label_block' => true,
                    'default' => esc_html__('Get started', 'traveler'),
                    'condition' => [
                        'id_package' => 'no'
                    ]
                ]
            );
            $this->add_control(
                'url_button',
                [
                    'label' => esc_html__('URL button', 'traveler'),
                    'description' => esc_html__('URL redirect', 'traveler'),
                    'type' => \Elementor\Controls_Manager::URL,
                    'label_block' => true,
                    'condition' => [
                        'id_package' => 'no'
                    ]
                ]
            );

            

            $this->end_controls_section();

            
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            $settings = array_merge(array('_element' => $this), $settings);
            ST_Elementor::view('table-pricing.template', $settings);
        }
    }
}
