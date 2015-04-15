jQuery( document ).ready(function( $ ) {

	$( '.scheme .points a' ).click( function(e) {
		e.preventDefault();
	} );

	var dlg = $( '<div class="property-building">' )
	.appendTo( 'body' );
	
	dlg.dialog( {
		'dialogClass' : 'wp-dialog',
		'modal' : true,
		'autoOpen' : false,
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
	} )

} );
