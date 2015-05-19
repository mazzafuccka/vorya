jQuery( document ).ready(function( $ ) {

	$( '.scheme .point' ).click( function(e) {
		e.preventDefault();

		$( '.scheme .property-info' ).hide( 'fast' );

		var $about = $( this ).parent( '.point-block' ).find( '.about' );
		
		$( '.scheme .property-info' ).html( $about.html() ).show( 'fast' );

	} );

} );
