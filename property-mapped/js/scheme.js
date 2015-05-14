jQuery( document ).ready(function( $ ) {

	$( '.scheme .point' ).click( function(e) {
		e.preventDefault();

		$( '.scheme .property-info' ).hide( 'fast' );

		var $about = $( this ).parent( '.point-block' ).find( '.about' );
		
		$( '.scheme .property-info' ).css( { 
			left: $( this ).position().left,
			top: $( this ).position().top + 20
		} ).html( $about.html() ).show( 'fast' );

	} );

} );
