export function validateComponentInit( name ) {
	const $body = $( 'body' );
	const initClass = `wpt-${name}-init`;

	if ( $body.hasClass( initClass ) ) {
		return true;
	}

	$body.addClass( initClass );

	return false;
}
