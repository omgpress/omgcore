export function validateComponentSetup( name ) {
	const $body = $( 'body' );
	const className = `has-wpappy-${name}`;

	if ( $body.hasClass( className ) ) {
		return true;
	}

	$body.addClass( className );

	return false;
}
