jQuery( document ).ready(function( $ ) {

	$( '.scheme .points > div > a' ).click( function(e) {
		e.preventDefault();

		$(this).next().dialog( {
			'dialogClass' : 'wp-dialog',
			'modal' : true,
			'autoOpen' : true,
			'closeOnEscape' : true,
			'buttons' : [
				{
					'text' : 'Close',
					'class' : 'button-primary',
					'click' : function() {
						$(this).dialog( 'close' );
					}
				}
			]
		} );
		
	} );

} );
