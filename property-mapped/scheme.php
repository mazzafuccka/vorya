<?php
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
<div class="row">
	<div class="col-xs-10">
		<div class="scheme">
			<img src="<?php echo plugins_url( '/images/' . $scheme . '.jpg', __FILE__ ); ?>">
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
				$property_sights = get_post_meta( $property->ID , 'imic_property_sights' , true );
				if ( ! empty( $property_building ) && $property_availability == 1 )
					$buildings[ $property_building ]['left-building'] ++;
					if ( ! empty( $property_floor ) )
						$buildings[ $property_building ]['left-floor'][ $property_floor ] ++;
				if ( $property_building != $_GET['building'] || $property_floor != $floor || empty($property_number_floor ) )
					continue;
				$position = $schemes[ $scheme ]['placements'][ $property_number_floor ]['position'];
				?>
				<div class="point-block">
					<button type="button" class="btn btn-default btn-lg point<?php echo $property_availability ? ' aviability' : ''; ?>" style="<?php echo 'left:' . $position[0] . '%;top:' . $position[1] . '%'; ?>" title="<?php echo esc_attr( $property_availability ? __( 'Aviable','framework' ) : __( 'Not aviable','framework' ) ); ?>">
					<?php echo $property_availability ? '+' : ''; ?>
					</button>
					<div class="about" style="display:none" data-title="<?php echo esc_attr( $property->post_title ); ?>">
						<div class="row">
							<div class="col-md-8">
								<p><?php echo __( 'Property','framework' ); ?>: <a href="<?php echo get_permalink( $property->ID ); ?>"><?php echo $property->post_title; ?></a></p>
								<ul>
									<li><?php echo __( 'Property Area','framework' ); ?>: <?php echo get_post_meta( $property->ID , 'imic_property_area' , true ); ?></li>
									<li><?php echo __( 'Beds','framework' ); ?>: <?php echo get_post_meta( $property->ID , 'imic_property_beds' , true ); ?></li>
									<li><?php echo __( 'Floor','framework' ); ?>: <?php echo $property_floor; ?></li>
								</ul>
								<a href="<?php echo get_permalink( $property->ID ); ?>" class="btn"><?php echo __( 'More','framework' ); ?></a>
							</div>
							<?php if (!empty($property_sights)) { ?>
							<div class="col-md-4">
								<img src="<?php echo wp_get_attachment_url( $property_sights ); ?>">
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php
			}
			wp_reset_postdata();
			?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 scheme-property-info" style="display:none"></div>
		</div>
	</div>
	<div class="col-xs-2 list-group list-floors pull-right">
	<?php
	$query = $_GET;
	for ( $f = $buildings[ $_GET['building'] ]['floors'] - 1; $f >= 0; $f-- ) {
		$query['floor'] = ($f + 1);
		$href = $_SERVER['PHP_SELF'] . '?' . http_build_query($query);
	?>
		<a href="<?php echo $href; ?>" class="list-group-item<?php echo $f == $floor - 1 ? ' active' : ''; ?>"><?php echo $f + 1; ?> <span class="badge"><?php echo $buildings[ $_GET['building'] ]['left-floor'][ $f + 1 ]; ?></span></a>
	<?php } ?>
	</div>
</div>