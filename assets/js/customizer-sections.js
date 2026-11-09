/* global jQuery */
( function ( $ ) {
	'use strict';

	function serialise( $list ) {
		var parts = [];
		$list.children( 'li' ).each( function () {
			var $li = $( this );
			parts.push( $li.data( 'key' ) + ':' + ( $li.find( 'input[type=checkbox]' ).prop( 'checked' ) ? '1' : '0' ) );
		} );
		$list.siblings( '.fc-sections-value' ).val( parts.join( ',' ) ).trigger( 'change' );
	}

	$( function () {
		$( '.fc-sections' ).each( function () {
			var $list = $( this );
			$list.sortable( { axis: 'y', update: function () { serialise( $list ); } } );
			$list.on( 'change', 'input[type=checkbox]', function () { serialise( $list ); } );
			$list.on( 'click', '.fc-up, .fc-down', function () {
				var $li = $( this ).closest( 'li' );
				if ( $( this ).hasClass( 'fc-up' ) ) {
					$li.prev().before( $li );
				} else {
					$li.next().after( $li );
				}
				$( this ).trigger( 'focus' );
				serialise( $list );
			} );
		} );
	} );
}( jQuery ) );
