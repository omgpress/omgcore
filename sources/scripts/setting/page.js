import 'jquery.are-you-sure';

export default function initPage() {
	$( '.wpt-page' ).areYouSure({
		'change': function() {
			if ( $( this ).hasClass( 'dirty' ) ) {
				$( '.wpt-submit-btn' ).prop( 'disabled', false );

			} else {
				$( '.wpt-submit-btn' ).prop( 'disabled', true );
			}
		}
	});
}
