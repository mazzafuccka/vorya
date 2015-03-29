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
		for ( var i = floors_count - 1; i > 0; i-- ) {
			var properties_count = ( Object.prototype.toString.call( left_floor ) === '[object Object]' && (i + 1).toString() in left_floor ) ? left_floor[ i + 1 ] : 0;
			$ul.append( '<li' + ( properties_count > 0 ? ' class="left-floor"' : '' ) + '><a href="#">' + ( properties_count > 0 ? '<span class="property-count">' + buildingMapL10n.available_apartments + ': ' + left_floor[ i + 1 ] : '' ) + '</span></a></li>' );
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
	} )
} );
