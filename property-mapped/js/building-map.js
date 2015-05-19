jQuery( document ).ready(function( $ ) {
	
	$( "img[usemap]" ).mapify( {
		popOver: {
			content: function( zone ) {
				return "<strong>" + zone.data( "title" ) + "</strong>";
			},
			delay: 0.7,
			margin: "15px",
			height: "70px",
			width: "260px"
		}
	} );
	
	$( 'map[name="building-maphilight"] area' ).click( function( e ) {
		e.preventDefault();
		dlg.text( '' );
		var $this = $( this );
		dlg.dialog( "option", "title", $this.data( 'alt' ) );
		console.log($this.data( 'alt' ));
		var $ul = $( '<div class="list-group">' ).appendTo( dlg );
		var floors_count = $this.data( 'floorsCount' );
		var left_floor = $this.data( 'leftFloor' );
		var url = window.location.href;
		url += url.split('?')[1] ? '&' : '?';
		for ( var i = floors_count - 1; i >= 0; i-- ) {
			var properties_count = ( Object.prototype.toString.call( left_floor ) === '[object Object]' && (i + 1).toString() in left_floor ) ? left_floor[ i + 1 ] : 0;
			var href = url + 'scheme=1&building=' + $this.data( 'id' ) + '&floor=' + (i + 1);
			$ul.append( '<a href="' + href + '" class="list-group-item list-group-item-' + ( properties_count > 0 ? 'success' : 'danger' ) + '" title="' + buildingMapL10n.available_apartments + ': ' + left_floor[ i + 1 ] + '"><span class="number">' + (i + 1) + '.</span>&nbsp;<span class="badge">' + left_floor[ i + 1 ] + '</span></a>' );
		}
		dlg.dialog( 'open' );
	} );
	
	var dlg = $( '<div class="floors-building">' )
	.appendTo( 'body' );
	
	dlg.dialog( {
		'dialogClass' : 'wp-dialog',
		'modal' : true,
		'autoOpen' : false,
		'closeOnEscape' : true
	} );
} );
