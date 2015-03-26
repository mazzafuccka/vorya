jQuery( document ).ready(function( $ ) {
	$( 'map[name="building-maphilight"] area' ).qtip( {
		style: {
			border: {
				width: 0,
				radius: 4
			},
			tip: true
		},
		position: {
			corner: {
				target: 'topMiddle',
				tooltip: 'center'
			}
		}
	} );
	$( '.building-map' ).maphilight( {
		fill: true,
		fillColor: 'ffffff',
		fillOpacity: 0.3,
		stroke: false,
		strokeColor: '8098b5',
		strokeOpacity: 1,
		strokeWidth: 1,
		fade: true,
		alwaysOn: false
	} );
	$( 'map[name="building-maphilight"] area' ).mouseover( function( e ) {
		console.log( 'mouseover' );
	} ).mouseout( function( e ) {
		console.log( 'mouseout' );
	} ).click( function( e ) {
		e.preventDefault();
		console.log( 'click' );
	} );
} );
