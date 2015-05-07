<?php
$buildings = include plugin_dir_path( __FILE__ ) . 'buildings.config.php';

$floor = (int) $_GET['floor'];

if ( ! array_key_exists( $_GET['building'], $buildings ) || $floor < 1 || $floor > $buildings[ $_GET['building'] ]['floors'] ) {
	echo 'Bad request';
	exit();
}

// get scheme
$scheme = '';
foreach( $buildings[ $_GET['building'] ]['schemes'] as $sch ) {
	if ( $floor < $sch[0] )
		break;
	$scheme = $sch[1];
}

if ( empty( $scheme ) ) {
	echo 'Does Not Found Scheme';
	exit();
}

$schemes = include plugin_dir_path( __FILE__ ) . 'schemes.config.php';
?>
<div class="scheme">
	<img src="<?php echo plugins_url( '/images/' . $scheme . '.png', __FILE__ ); ?>">
	<div class="points">
<?php
		$args = array( 'post_type' => 'property' , 'post_status' => 'publish', 'numberposts' => -1 );
		$properties = get_posts( $args );
		foreach( $properties as $property ) {
			setup_postdata($property);
			$property_building = get_post_meta( $property->ID , 'imic_property_building' , true );
			$property_floor = get_post_meta( $property->ID , 'imic_property_floor' , true );
			$property_number_floor = get_post_meta( $property->ID , 'imic_property_number_floor' , true );
			$property_availability = get_post_meta( $property->ID , 'imic_property_availability' , true );
			if ( $property_building != $_GET['building'] || $property_floor != $floor || empty($property_number_floor ) )
				continue;
			$position = $schemes[ $scheme ]['placements'][ $property_number_floor ]['position'];
			?>
		<div class="point-block">
			<button type="button" class="btn btn-default btn-lg point<?php echo $property_availability ? ' aviability' : ''; ?>" style="<?php echo 'left:' . $position[0] . 'px;top:' . $position[1] . 'px'; ?>" title="<?php echo esc_attr( $property_availability ? __( 'Aviable','framework' ) : __( 'Not aviable','framework' ) ); ?>">
				<?php echo $property_availability ? '+' : ''; ?>
			</button>
			<div class="about" style="display:none" data-title="<?php echo esc_attr( $property->post_title ); ?>">
				<p><?php echo __( 'Property','framework' ); ?>: <a href="<?php echo get_permalink( $property->ID ); ?>"><?php echo $property->post_title; ?></a></p>
				<ul>
					<li><a href="#"><?php echo __( 'How to buy','framework' ); ?></a></li>
					<li><a href="#"><?php echo __( 'Example of finishes','framework' ); ?></a></li>
				</ul>
			</div>
		</div>
			<?php
		}
		wp_reset_postdata();
		?>
	</div>
</div>
