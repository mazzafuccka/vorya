<?php
$buildings = include plugin_dir_path( __FILE__ ) . 'buildings.config.php';

$floor = (int) $_GET['floor'];

if ( ! array_key_exists( $_GET['building'], $buildings ) || $floor < 1 || $floor > $buildings[ $_GET['building'] ]['floors'] ) {
	header( 'HTTP/1.1 400 Bad Request' );
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
	header( 'HTTP/1.1 500 Does Not Found Scheme' );
	echo 'Does Not Found Scheme';
	exit();
}

$schemes = include plugin_dir_path( __FILE__ ) . 'schemes.config.php';
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Scheme</title>
</head>

<body>
<img src="<?php echo plugins_url( '/images/' . $scheme . '.png', __FILE__ ); ?>" class="scheme-map" usemap="#scheme-maphilight">
<map name="scheme-maphilight">
<?php
		query_posts( array( 'post_type' => 'property' , 'post_status' => 'publish' ) );
		while ( have_posts() ) {
			the_post();
			$property_building = get_post_meta( get_the_ID() , 'imic_property_building' , true );
			$property_floor = get_post_meta( get_the_ID() , 'imic_property_floor' , true );
			$property_number = get_post_meta( get_the_ID() , 'imic_property_number' , true );
			$property_availability = get_post_meta( get_the_ID() , 'imic_property_availability' , true );
			if ($property_building != $_GET['building'] || $property_floor != $floor || empty($property_number))
				continue;
			?>
	<area href="#" shape="poly" coords="<?php echo $schemes[ $scheme ]['properties'][ $property_number ]['coords']; ?>" alt="<?php echo esc_attr( __( 'Property', 'framework' ).' '/*.$name*/ ); ?>" title="<?php echo esc_attr( $property_availability ? __( 'Aviable','framework' ) : __( 'Not aviable','framework' ) ); ?>">
			<?php
		}
		wp_reset_query();
		?>
</map>
</body>
</html>
