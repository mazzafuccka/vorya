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
}
 
$wppropertymapped = new WPPropertyMapped();
