<?php
/**
 * Plugin Name: Property Mapped
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

register_activation_hook(   __FILE__, array( 'WPPropertyMapped', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'WPPropertyMapped', 'on_deactivation' ) );
register_uninstall_hook(    __FILE__, array( 'WPPropertyMapped', 'on_uninstall' ) );

add_action( 'plugins_loaded', array( 'WPPropertyMapped', 'init' ) );

class WPPropertyMapped {

	protected static $instance;

    public function __construct()
    {
		add_action( 'admin_init', array( &$this, 'add_additional_fields' ));
		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );
		add_action( 'property_mapped', array( &$this, 'echo_mapped' ) );
    }

	public static function init()
	{
		is_null( self::$instance ) AND self::$instance = new self;
		return self::$instance;
	}

	public static function on_activation()
	{
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "activate-plugin_{$plugin}" );
	}

	public static function on_deactivation()
	{
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
		$plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
		check_admin_referer( "deactivate-plugin_{$plugin}" );
	}

	public static function on_uninstall()
	{
		if ( ! current_user_can( 'activate_plugins' ) )
			return;
		check_admin_referer( 'bulk-plugins' );

		if ( __FILE__ != WP_UNINSTALL_PLUGIN )
			return;
	}

	function enqueue_scripts() {
		wp_register_script( 'mapify', plugins_url( '/js/jquery.mapify.min.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'building-map', plugins_url( '/js/building-map.js', __FILE__ ), array( 'jquery', 'mapify', 'jquery-ui-dialog' ) );

		wp_register_script( 'building-scheme', plugins_url( '/js/scheme.js', __FILE__ ), array( 'jquery', 'jquery-ui-dialog' ) );

		wp_register_style( 'mapify', plugins_url( '/css/jquery.mapify.min.css', __FILE__ ), array( ) );
		wp_register_style( 'building-map', plugins_url( '/css/building-map.css', __FILE__ ), array( 'wp-jquery-ui-dialog', 'mapify' ) );
		
		wp_register_style( 'building-scheme', plugins_url( '/css/scheme.css', __FILE__ ), array( 'wp-jquery-ui-dialog' ) );

		if ( isset( $_GET['scheme'] ) ) {
			wp_enqueue_script( 'building-scheme' );
			wp_enqueue_style( 'building-scheme' );
		} else {
			wp_enqueue_script( 'building-map' );
			wp_localize_script( 'building-map', 'buildingMapL10n', array(
				'available_apartments'  => __( 'Available apartments', 'building-map' )
			) );
			wp_enqueue_style( 'building-map' );
		}
	}

	function add_additional_fields() {
		global $meta_boxes;

		$prefix = 'imic_';
		
		// Property Building
		$meta_boxes[16]['fields'][] = array(
			'name' => __( 'Building', 'framework' ),
			'id' => $prefix . 'property_building',
			'desc' => __( "Enter the Number of Building.", 'framework' ),
			'type' => 'text',
			'std' => '',
		);

		// Property Floor
		$meta_boxes[16]['fields'][] = array(
			'name' => __( 'Floor', 'framework' ),
			'id' => $prefix . 'property_floor',
			'desc' => __( "Enter the Number of Floor.", 'framework' ),
			'type' => 'text',
			'std' => '',
		);

		// Property Number
		$meta_boxes[16]['fields'][] = array(
			'name' => __( 'Number on Floor', 'framework' ),
			'id' => $prefix . 'property_number_floor',
			'desc' => __( "Enter the Number of Placement on Floor.", 'framework' ),
			'type' => 'text',
			'std' => '',
		);
		
		// Property Availability
		$meta_boxes[16]['fields'][] = array(
			'name' => __( 'Property Availability', 'framework' ),
			'id' => $prefix . 'property_availability',
			'desc' => __( 'Select Yes if this property is available.', 'framework' ),
			'type' => 'select',
			'options' => array(
				'0' => __( 'No', 'framework' ),
				'1' => __( 'Yes', 'framework' ),
			)
		);

	}
	
	function echo_mapped() {
		if ( isset( $_GET['scheme'] ) ) {
			include plugin_dir_path( __FILE__ ) . 'scheme.php';
		} else {
			include plugin_dir_path( __FILE__ ) . 'building-map.php';
		}
    }
}
