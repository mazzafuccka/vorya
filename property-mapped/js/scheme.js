jQuery( document ).ready(function( $ ) {

	$( '.scheme .point' ).click( function(e) {
		e.preventDefault();

		var $about = $( this ).parent( '.point-block' ).find( '.about' );
		
		dlg
		.dialog('option', 'title', $about.data( 'title' ) )
		.html( $about.html() )
		.dialog( 'open' );

	} );

	var dlg = $( '<div class="floors-building">' )
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
	} );

} );
