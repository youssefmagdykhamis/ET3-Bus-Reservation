<?php
use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if (!class_exists('ST_Banner_Form_Element')) {
    class ST_Banner_Form_Element extends \Elementor\Widget_Base
    {

        public function get_name()
        {
            return 'st_banner_form';
        }

        public function get_title()
        {
            return esc_html__('Form Search', 'traveler');
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
            return ['st-banner-form'];
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
                'type_form',
                [
                    'label' => esc_html__('Type form search', 'traveler'),
                    'type' => 'select',
                    'label_block' => true,
                    'options' => [
                        'single'  => esc_html__( 'Single', 'traveler' ),
                        'mix_service' => esc_html__( 'Mix service', 'traveler' ),
                    ],
                    'default' => 'single',
                    'frontend_available' => true
                ]
            );
            $this->add_control(
                'services',
                [
                    'label' => esc_html__( 'Choose service', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => ST_Elementor::listSerrviceSelectionName(),
                    'default' => [ 'st_hotel'],
                    'condition' => [
                        'type_form' => 'mix_service'
                    ]
                ]
            );
            $this->add_control(
                'service',
                [
                    'label' => esc_html__( 'Choose service', 'traveler' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => ST_Elementor::listSerrviceSelectionName(),
                    'default' => 'st_hotel',
                    'condition' => [
                        'type_form' => 'single'
                    ]
                ]
            );


            $this->end_controls_section();
            

            
        }

        protected function render()
        {
            $settings = $this->get_settings_for_display();

            $settings = array_merge(array('_element' => $this), $settings);
            ST_Elementor::view('banner-form.template', $settings);
        }
    }
}
