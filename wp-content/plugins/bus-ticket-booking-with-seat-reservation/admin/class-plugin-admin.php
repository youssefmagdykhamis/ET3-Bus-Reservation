<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 * @package    WBTM_Plugin
 * @subpackage WBTM_Plugin/admin
 * @author     MagePeople team <magepeopleteam@gmail.com>
 */
class WBTM_Plugin_Admin {

	private $plugin_name;

	private $version;

	public function __construct() {

		$this->load_admin_dependencies();
		add_action( 'admin_enqueue_scripts',array($this,'enqueue_styles' ));
		add_action( 'admin_enqueue_scripts',array($this,'enqueue_scripts' ));
		add_action('admin_enqueue_scripts',array($this,'wbtm_ajax_call_url'));
		add_action('wp_enqueue_scripts',array($this,'wbtm_ajax_call_url'));		
	}
	
	function wbtm_ajax_call_url(){
		wp_localize_script('wbtm_ajax_enq', 'wbtm_ajax', array( 'wbtm_ajaxurl' => admin_url( 'admin-ajax.php')));
		wp_enqueue_script( 'wbtm_ajax_enq' );

	}

	public function enqueue_styles() {
		wp_enqueue_style('mage-jquery-ui-style',WBTM_PLUGIN_URL.'admin/css/jquery-ui.css',array());	
        wp_enqueue_style('pickplugins-options-framework', WBTM_PLUGIN_URL.'admin/assets/css/pickplugins-options-framework.css');
		wp_enqueue_style('jquery-ui', WBTM_PLUGIN_URL.'admin/assets/css/jquery-ui.css');
		wp_enqueue_style('select2.min', WBTM_PLUGIN_URL.'admin/assets/css/select2.min.css');
		wp_enqueue_style('codemirror', WBTM_PLUGIN_URL.'admin/assets/css/codemirror.css');
		wp_enqueue_style('font-awesome-css-cdn', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.2.0/css/all.min.css", null, 1);
		wp_enqueue_style( 'mage-admin-css', WBTM_PLUGIN_URL . 'admin/css/mage-plugin-admin.css', array(), time(), 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core'); 
		wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script('magepeople-options-framework', plugins_url( 'assets/js/pickplugins-options-framework.js' , __FILE__ ) , array( 'jquery' ));

		wp_register_script('multidatepicker-wbtm', 'https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js', array('jquery'), 1, true);
 		wp_enqueue_script('multidatepicker-wbtm');

        wp_localize_script( 'PickpluginsOptionsFramework', 'PickpluginsOptionsFramework_ajax', array( 'PickpluginsOptionsFramework_ajaxurl' => admin_url( 'admin-ajax.php')));
        wp_enqueue_script('select2.min', plugins_url( 'assets/js/select2.min.js' , __FILE__ ) , array( 'jquery' ));
        wp_enqueue_script('codemirror', WBTM_PLUGIN_URL.'admin/assets/js/codemirror.min.js', array( 'jquery' ),null, false);
        wp_enqueue_script('form-field-dependency', plugins_url( 'assets/js/form-field-dependency.js' , __FILE__ ) , array( 'jquery' ),null, false);
		wp_enqueue_script( 'mage-plugin-js', WBTM_PLUGIN_URL . 'admin/js/mage-plugin-admin.js', array( 'jquery','jquery-ui-core','jquery-ui-datepicker' ), time(), true );		
	}



	private function load_admin_dependencies() {
		require_once WBTM_PLUGIN_DIR . 'admin/class/class-create-cpt.php';
		require_once WBTM_PLUGIN_DIR . 'admin/class/class-create-tax.php';
		require_once WBTM_PLUGIN_DIR . 'admin/class/class-meta-box.php';

		require_once WBTM_PLUGIN_DIR . 'admin/class/class-setting-page.php';
		// require_once WBTM_PLUGIN_DIR . 'admin/class/class-menu-page.php';
		require_once WBTM_PLUGIN_DIR . 'admin/class/class-tax-meta.php';
		require_once WBTM_PLUGIN_DIR . 'admin/class/class-custom-css.php';
	}



}
new WBTM_Plugin_Admin();