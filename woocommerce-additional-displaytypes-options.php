<?php
/* 
*	Plugin Name: WooCommerce Customized Category
*	Description: This plugin adds display type options to your WooCommerce.
*	Version: 1.1.1
*	Author: Ralph Rezende Larsen
*	Author URI: https://sitewalk.one
*/

include(plugin_dir_path( __FILE__).'php/wado-classes.php');
include(plugin_dir_path( __FILE__).'php/wado-functions.php');

if(! defined( 'ABSPATH' )) exit; // Exit if accessed directly

class Woocommerce_additional_displaytype_options {
	public function __construct() {
		// Admin
		
		// Add the new display types
		add_filter('woocommerce_product_settings', array($this, 'add_products_display_options_setting')); 
		
		// Add the new display types manually to the cat forms
		add_action('product_cat_add_form_fields', array( &$this, 'append_category_display_types' ), 50, 3);
		add_action('product_cat_edit_form_fields', array( &$this, 'append_category_display_types' ), 50, 3);

		// Additionals
		add_action('plugins_loaded', array(&$this, 'load_textdomain'));
		add_action('wp_enqueue_scripts', array(&$this, 'wado_load_css'));
		add_action('wp_enqueue_scripts', array(&$this, 'wado_load_scripts'));
		
		
		// Shop

		// Change archive description
		//add_action('woocommerce_archive_description', array(&$this, 'archive_description'), 10, 2);
		//add_filter('the_content', array(&$this, 'change_description'), 100, 3);
		//add_filter('category_description', array(&$this, 'change_description'), 50, 2);
		//add_action('woocommerce_archive_description', array(&$this, 'change_description'), 1, 2);
		
		// Override template from plugin
		add_filter('woocommerce_locate_template', array(&$this, 'this_plugin_woocommerce_locate_template'), 10, 3);

		// override the function woocommerce_taxonomy_archive_description
		//add_action('init', array($this, 'remove_woocommerce_taxonomy_archive_description'));
		//add_action('init', array($this, 'my_taxonomy_archive_description'));
		//add_action('init', array($this, 'remove_woocommerce_product_archive_description'));
		//add_action('init', array($this, 'my_product_archive_description'));
		//add_action('init', array($this, 'my_product_archive_description'));
	}
	
	// General
	public static function myplugin_plugin_path() {
		return untrailingslashit(plugin_dir_path(__FILE__));
	}
	
	// Admin
    public static function add_products_display_options_setting($settings) {
		$updated_settings = array();

		foreach ( $settings as $section ) {
			// Add display types to the dropdowns
			if (isset( $section['id'] ) && ('woocommerce_shop_page_display' == $section['id'] || 'woocommerce_category_archive_display' == $section['id'])) {
				$section["options"]["subcategoriesindexed"] = 'Show categories indexed';
				$section["options"]["bothsubcategoriesindexed"] = 'Show categories indexed &amp; products';
			}

			$updated_settings[] = $section;

		}

		return $updated_settings;
    }
    
	public static function append_category_display_types($term) {
		$display_type = null;
		if (isset($term->term_id)) {
			$display_type = get_woocommerce_term_meta($term->term_id, 'display_type', true);
		}
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				if ( jQuery( '#display_type' ).length > 0 ) {
					jQuery( '#display_type' ).append('<option value="subcategoriesindexed" <?php if ('subcategoriesindexed' == $display_type) echo 'selected="selected"' ?>><?php _e('Subcategories (indexed)', 'wado'); ?></option>');
					jQuery( '#display_type' ).append('<option value="bothsubcategoriesindexed" <?php if ('bothsubcategoriesindexed' == $display_type) echo 'selected="selected"' ?>><?php _e('Both (subcategories indexed)', 'wado'); ?></option>');
				}
			});
		</script>
		<?php
    }

	// load assets
	public function load_textdomain(){
		load_plugin_textdomain('wado', false, dirname(plugin_basename(__FILE__)).'/i18n/');
	}
	
	public function wado_load_css(){
		wp_register_style('wado_admin_css', plugins_url('css/wado-admin.css',__FILE__ ));
		wp_enqueue_style('wado_admin_css');

		wp_register_style('wado_shop_css', plugins_url('css/wado-shop.css',__FILE__ ));
		wp_enqueue_style('wado_shop_css');
	}
	
	public function wado_load_scripts(){
		wp_register_script('wado_script_js',plugins_url('/js/wado-script.js', __FILE__),array('jquery', 'jquery-ui-tooltip'));
	    wp_enqueue_script('wado_script_js'); 
	}
	
	
	// Shop

	public static function this_plugin_woocommerce_locate_template($template, $template_name, $template_path) {
		global $woocommerce;

		$_template = $template;

		if (!$template_path) $template_path = $woocommerce->template_url;

		$plugin_path  = self::myplugin_plugin_path().'/woocommerce/';


		// Look within passed path within the theme - this is last priority
		$template = locate_template(
			array($template_path.$template_name, $template_name)
		);

		// Modification: Get the template from this plugin, if it exists this is second priority
		if (!$template && file_exists($plugin_path.$template_name)) $template = $plugin_path.$template_name;

		// Use default template as first priority
		if (!$template) $template = $_template;
		
		return $template;
	}
}
$Woocommerce_additional_displaytype_options = new Woocommerce_additional_displaytype_options();