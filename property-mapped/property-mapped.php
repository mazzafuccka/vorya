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
		wp_register_script('jquery_maphilight', plugins_url( '/js/jquery.maphilight.js', __FILE__ ), array( 'jquery' ) );
		wp_register_script('jquery_building_map', plugins_url( '/js/building-map.js', __FILE__ ), array( 'jquery' ) );
		
		wp_enqueue_script('jquery_maphilight');
		wp_enqueue_script('jquery_building_map');
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
		$buildings = array(
			'building-1' => array(
				'floors' => 20,
				'deadline' => new DateTime('2015-04-15'),
				'coords' => '47,62,106,61,134,72,135,118,30,116',
			),
			'building-2' => array(
				'floors' => 15,
				'deadline' => new DateTime('2015-10-01'),
				'coords' => '32,157,133,157,127,192,127,211,28,211',
			)
		);
		query_posts( array( 'post_type' => 'property' , 'post_status' => 'publish' ) );
		while ( have_posts() ) {
			the_post();
			$property_building = get_post_meta( get_the_ID() , 'imic_property_building' , true );
			$property_availability = get_post_meta( get_the_ID() , 'imic_property_availability' , true );
			if ( ! empty( $property_building ) && $property_availability == 1 )
			$buildings[ $property_building ]['remaining'] ++;
		}
		//$this->add_additional_fields();
		wp_reset_query();
		?>
<img src="<?php echo plugins_url( '/images/demo_simple.png', __FILE__ ); ?>" width="177" height="261" class="building-map" usemap="#building-maphilight">
	<map name="building-maphilight">
<?php foreach($buildings as $building): ?>
		<area href="#" shape="poly" coords="<?php echo $building['coords']; ?>" alt="<?php echo __('Building','framework').' '/*.$name*/; ?>" title="<?php echo __('Number of remaining flats','framework').': '.$building['remaining'].'&#013;'.__('Turn of building','framework').': '.human_time_diff( current_time('timestamp'), $building['deadline']->getTimestamp() ); ?>">
<?php endforeach; ?>
	</map>
<?php
    }
}

$wppropertymapped = new WPPropertyMapped();
