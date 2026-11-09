/* Faridpur City — front-end behaviour. No dependencies. */
( function () {
	'use strict';
	var i18n = window.fcTheme || {};

	/* Mobile navigation drawer */
	var toggle = document.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'primary-nav' );
	var backdrop = document.querySelector( '.nav-backdrop' );
	var closeBtn = nav ? nav.querySelector( '.nav-close' ) : null;

	function focusables() {
		return nav.querySelectorAll( 'a[href], button:not([disabled])' );
	}
	function openNav() {
		nav.classList.add( 'is-open' );
		document.body.classList.add( 'nav-open' );
		backdrop.hidden = false;
		toggle.setAttribute( 'aria-expanded', 'true' );
		toggle.setAttribute( 'aria-label', i18n.closeMenu || 'Close menu' );
		var f = focusables();
		if ( f.length ) { f[ 0 ].focus(); }
	}
	function closeNav( returnFocus ) {
		nav.classList.remove( 'is-open' );
		document.body.classList.remove( 'nav-open' );
		backdrop.hidden = true;
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-label', i18n.openMenu || 'Open menu' );
		if ( returnFocus ) { toggle.focus(); }
	}
	if ( toggle && nav && backdrop ) {
		toggle.addEventListener( 'click', function () {
			if ( nav.classList.contains( 'is-open' ) ) { closeNav( true ); } else { openNav(); }
		} );
		backdrop.addEventListener( 'click', function () { closeNav( true ); } );
		if ( closeBtn ) { closeBtn.addEventListener( 'click', function () { closeNav( true ); } ); }
		nav.addEventListener( 'keydown', function ( e ) {
			if ( ! nav.classList.contains( 'is-open' ) ) { return; }
			if ( e.key === 'Escape' ) { closeNav( true ); return; }
			if ( e.key === 'Tab' ) {
				var f = focusables();
				var first = f[ 0 ];
				var last = f[ f.length - 1 ];
				if ( e.shiftKey && document.activeElement === first ) { e.preventDefault(); last.focus(); }
				else if ( ! e.shiftKey && document.activeElement === last ) { e.preventDefault(); first.focus(); }
			}
		} );
		window.matchMedia( '(min-width: 1024px)' ).addEventListener( 'change', function ( mq ) {
			if ( mq.matches && nav.classList.contains( 'is-open' ) ) { closeNav( false ); }
		} );
	}

	/* Hero slider: manual controls only (no autoplay), keyboard arrows, swipe. */
	var track = document.querySelector( '[data-hero]' );
	if ( track ) {
		var slides = Array.prototype.slice.call( track.querySelectorAll( '.hero__slide' ) );
		var status = document.querySelector( '.hero__status' );
		var current = 0;
		var toBn = function ( n ) {
			if ( document.documentElement.lang.indexOf( 'bn' ) !== 0 ) { return String( n ); }
			return String( n ).replace( /\d/g, function ( d ) { return '০১২৩৪৫৬৭৮৯'[ d ]; } );
		};
		var show = function ( i ) {
			current = ( i + slides.length ) % slides.length;
			slides.forEach( function ( s, idx ) {
				var on = idx === current;
				s.hidden = ! on;
				s.classList.toggle( 'is-active', on );
			} );
			if ( status ) {
				status.textContent = ( i18n.slideOf || '%1$s / %2$s' ).replace( '%1$s', toBn( current + 1 ) ).replace( '%2$s', toBn( slides.length ) );
			}
		};
		if ( slides.length > 1 ) {
			var prev = document.querySelector( '.hero__prev' );
			var next = document.querySelector( '.hero__next' );
			if ( prev ) { prev.addEventListener( 'click', function () { show( current - 1 ); } ); }
			if ( next ) { next.addEventListener( 'click', function () { show( current + 1 ); } ); }
			track.addEventListener( 'keydown', function ( e ) {
				if ( e.key === 'ArrowLeft' ) { show( current - 1 ); }
				if ( e.key === 'ArrowRight' ) { show( current + 1 ); }
			} );
			var x0 = null;
			track.addEventListener( 'touchstart', function ( e ) { x0 = e.touches[ 0 ].clientX; }, { passive: true } );
			track.addEventListener( 'touchend', function ( e ) {
				if ( x0 === null ) { return; }
				var dx = e.changedTouches[ 0 ].clientX - x0;
				if ( Math.abs( dx ) > 40 ) { show( current + ( dx < 0 ? 1 : -1 ) ); }
				x0 = null;
			}, { passive: true } );
			show( 0 );
		}
	}

	/* Click-to-load maps */
	document.querySelectorAll( '.map-embed__load' ).forEach( function ( btn ) {
		btn.addEventListener( 'click', function () {
			var box = btn.closest( '.map-embed' );
			var src = box.getAttribute( 'data-map-src' );
			if ( ! src || src.indexOf( 'https://maps.google.com/' ) !== 0 ) { return; }
			var frame = document.createElement( 'iframe' );
			frame.src = src;
			frame.loading = 'lazy';
			frame.referrerPolicy = 'no-referrer-when-downgrade';
			frame.title = i18n.mapTitle || 'Map';
			box.textContent = '';
			box.classList.add( 'is-loaded' );
			box.appendChild( frame );
		} );
	} );
}() );
