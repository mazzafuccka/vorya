﻿<?php
$args = array( 'post_type' => 'property' , 'post_status' => 'publish', 'numberposts' => -1 );
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
<div class="building-map-wrapper">
	<img src="<?php echo plugins_url( '/images/ACCamera_11_2560.jpg', __FILE__ ); ?>" class="building-map" width="2560" height="664" border="0" usemap="#building-maphilight">
	<map name="building-maphilight" id="building-maphilight">
<?php foreach( $buildings as $id => $building ): ?>
		<area href="#" shape="poly" coords="<?php echo $building['coords']; ?>" data-alt="<?php echo esc_attr( __( 'Building', 'framework' ).' ' . $building['title'] ); ?>" title="<?php echo esc_attr( __( 'Number remaining flats','framework' ).': '.$building['left-building'].'<br>'.__( 'Turn building', 'framework' ).': '.human_time_diff( current_time( 'timestamp' ), $building['deadline']->getTimestamp() ) ); ?>" data-floors-count="<?php echo $building['floors']; ?>" data-left-floor="<?php echo esc_attr( json_encode( $building['left-floor'] ) ); ?>" data-id="<?php echo $id; ?>">
<?php endforeach; ?>
	</map>
	<div class="map-overlay">
		<div class="compass"></div>
		<div class="how-to-buy">
			<a href="#" class="btn btn-info" role="button">Как приобрести</a>
		</div>
		<ul class="nav nav-pills">
			<li role="presentation" class="active"><a href="#">Квартиры</a></li>
			<li role="presentation"><a href="#">Парковка</a></li>
		</ul>
		<div class="labels">
<?php foreach( $buildings as $id => $building ): 
			$position = $building['label']['position'];
?>
			<span class="label label-info" style="<?php echo 'left:' . $position[0] . '%;top:' . $position[1] . '%'; ?>"><?php echo $building['title']; ?></span>
<?php endforeach; ?>
		</div>
	</div>
</div>