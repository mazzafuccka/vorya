<?php
/**
 * Plugin Name: Property Mapped
 * Version: 1.0.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class WPPropertyMapped {
    public function __construct()
    {
		add_action( 'admin_init', array( &$this, 'add_additional_fields' ));

		add_action( 'wp_enqueue_scripts', array( &$this, 'enqueue_scripts' ) );

		add_action( 'property_mapped', array( &$this, 'echo_mapped' ) );
    }
	
	function enqueue_scripts() {
		wp_register_script( 'maphilight', plugins_url( '/js/jquery.maphilight.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'qtip', plugins_url( '/js/jquery.qtip-1.0.0-rc3.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script( 'building-map', plugins_url( '/js/building-map.js', __FILE__ ), array( 'jquery', 'qtip', 'maphilight' ) );

		wp_enqueue_script( 'building-map' );
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
			$property_availability = get_post_meta( get_the_ID() , 'imic_property_availability' , true );
			if ( ! empty( $property_building ) && $property_availability == 1 )
			$buildings[ $property_building ]['remaining'] ++;
		}
		wp_reset_query();
		?>
<img src="<?php echo plugins_url( '/images/ACCamera_10.jpg', __FILE__ ); ?>" width="900" height="600" class="building-map" usemap="#building-maphilight">
<map name="building-maphilight">
<?php foreach( $buildings as $building ): ?>
	<area href="#" shape="poly" coords="<?php echo $building['coords']; ?>" alt="<?php echo esc_attr( __( 'Building', 'framework' ).' '/*.$name*/ ); ?>" title="<?php echo esc_attr( __( 'Number remaining flats','framework' ).': '.$building['remaining'].'<br>'.__( 'Turn building', 'framework' ).': '.human_time_diff( current_time( 'timestamp' ), $building['deadline']->getTimestamp() ) ); ?>">
<?php endforeach; ?>
</map>
<?php
    }
}

$wppropertymapped = new WPPropertyMapped();
