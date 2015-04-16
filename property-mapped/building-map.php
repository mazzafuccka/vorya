﻿<?php
$buildings = include plugin_dir_path( __FILE__ ) . 'buildings.config.php';

array_walk( $buildings, function (&$building, $id) {
	
	$building['left-building'] = 0;
	
	for ( $i = 0; $i < $building['floors']; $i++ ) {
		$building['left-floor'][ $i + 1 ] = 0;
	}
} );

$args = array( 'post_type' => 'property' , 'post_status' => 'publish' );
$properties = get_posts( $args );

foreach( $properties as $property ) {
	setup_postdata($property);
	$property_building = get_post_meta( $property->ID , 'imic_property_building' , true );
	$property_floor = get_post_meta( $property->ID , 'imic_property_floor' , true );
	$property_availability = get_post_meta( $property->ID , 'imic_property_availability' , true );
	if ( ! empty( $property_building ) && $property_availability == 1 )
		$buildings[ $property_building ]['left-building'] ++;
		if ( ! empty( $property_floor ) )
			$buildings[ $property_building ]['left-floor'][ $property_floor ] ++;
}
wp_reset_postdata();
		?>
<img src="<?php echo plugins_url( '/images/ACCamera_10.jpg', __FILE__ ); ?>" class="building-map" usemap="#building-maphilight">
<map name="building-maphilight">
<?php foreach( $buildings as $id => $building ): ?>
	<area href="#" shape="poly" coords="<?php echo $building['coords']; ?>" alt="<?php echo esc_attr( __( 'Building', 'framework' ).' '/*.$name*/ ); ?>" title="<?php echo esc_attr( __( 'Number remaining flats','framework' ).': '.$building['left-building'].'<br>'.__( 'Turn building', 'framework' ).': '.human_time_diff( current_time( 'timestamp' ), $building['deadline']->getTimestamp() ) ); ?>" data-floors-count="<?php echo $building['floors']; ?>" data-left-floor="<?php echo esc_attr( json_encode( $building['left-floor'] ) ); ?>" data-id="<?php echo $id; ?>">
<?php endforeach; ?>
</map>