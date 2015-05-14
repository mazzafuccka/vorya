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
	
	$( 'map[name="building-maphilight"] area' ).mouseover( function( e ) {
		//console.log( 'mouseover' );
	} ).mouseout( function( e ) {
		//console.log( 'mouseout' );
	} ).click( function( e ) {
		e.preventDefault();
		dlg.text( '' );
		var $ul = $( '<ul>' ).appendTo( dlg );
		var $this = $( this );
		var floors_count = $this.data( 'floorsCount' );
		var left_floor = $this.data( 'leftFloor' );
		var url = window.location.href;
		url += url.split('?')[1] ? '&' : '?';
		for ( var i = floors_count - 1; i >= 0; i-- ) {
			var properties_count = ( Object.prototype.toString.call( left_floor ) === '[object Object]' && (i + 1).toString() in left_floor ) ? left_floor[ i + 1 ] : 0;
			$ul.append( '<li' + ( properties_count > 0 ? ' class="aviability"' : '' ) + '><span class="number">' + (i + 1) + '</span> <a href="' + url + 'scheme=1&building=' + $this.data( 'id' ) + '&floor=' + (i + 1) + '"><span class="property-count">' + buildingMapL10n.available_apartments + ': ' + left_floor[ i + 1 ] + '</span></a></li>' );
		}
		dlg.dialog( 'open' );
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
