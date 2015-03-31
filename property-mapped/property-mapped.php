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
		
		add_action( 'init', array( &$this, 'scheme_rewrite' ) );
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

	function scheme_rewrite() {
		if ( isset( $_GET['scheme'] ) ) {
			include plugin_dir_path( __FILE__ ) . 'scheme.php';
			exit();
		}
	}

	function enqueue_scripts() {
		wp_register_script( 'maphilight', plugins_url( '/js/jquery.maphilight.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'qtip', plugins_url( '/js/jquery.qtip-1.0.0-rc3.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'building-map', plugins_url( '/js/building-map.js', __FILE__ ), array( 'jquery', 'qtip', 'maphilight', 'jquery-ui-dialog' ) );
		wp_register_style( 'building-map', plugins_url( '/css/building-map.css', __FILE__ ), array( 'wp-jquery-ui-dialog' ) );

		wp_enqueue_script( 'building-map' );
		wp_localize_script( 'building-map', 'buildingMapL10n', array(
			'available_apartments'  => __( 'Available apartments', 'building-map' )
		) );
		wp_enqueue_style( 'building-map' );
	}

	function add_additional_fields() {
		global $meta_boxes;

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
			'name' => __( 'Number', 'framework' ),
			'id' => $prefix . 'property_number',
			'desc' => __( "Enter the Number of Property.", 'framework' ),
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
		$buildings = include plugin_dir_path( __FILE__ ) . 'buildings.config.php';

		query_posts( array( 'post_type' => 'property' , 'post_status' => 'publish' ) );
		while ( have_posts() ) {
			the_post();
			$property_building = get_post_meta( get_the_ID() , 'imic_property_building' , true );
			$property_floor = get_post_meta( get_the_ID() , 'imic_property_floor' , true );
			$property_availability = get_post_meta( get_the_ID() , 'imic_property_availability' , true );
			if ( ! empty( $property_building ) && $property_availability == 1 )
				$buildings[ $property_building ]['left-building'] ++;
				if ( ! empty( $property_floor ) )
					$buildings[ $property_building ]['left-floor'][ $property_floor ] ++;
		}
		wp_reset_query();
		?>
<img src="<?php echo plugins_url( '/images/ACCamera_10.jpg', __FILE__ ); ?>" class="building-map" usemap="#building-maphilight">
<map name="building-maphilight">
<?php foreach( $buildings as $id => $building ): ?>
	<area href="#" shape="poly" coords="<?php echo $building['coords']; ?>" alt="<?php echo esc_attr( __( 'Building', 'framework' ).' '/*.$name*/ ); ?>" title="<?php echo esc_attr( __( 'Number remaining flats','framework' ).': '.$building['left-building'].'<br>'.__( 'Turn building', 'framework' ).': '.human_time_diff( current_time( 'timestamp' ), $building['deadline']->getTimestamp() ) ); ?>" data-floors-count="<?php echo $building['floors']; ?>" data-left-floor="<?php echo esc_attr( json_encode( $building['left-floor'] ) ); ?>" data-id="<?php echo $id; ?>">
<?php endforeach; ?>
</map>
<?php
    }
}

//$wppropertymapped = new WPPropertyMapped();
