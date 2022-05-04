export default function toggleControls() {
	wp.customize( 'wpt_header_body_logo_enable', function( setting ) {
		wp.customize.control( 'wpt_header_body_logo', toggle( setting, [ 'enabled' ]) );
		wp.customize.control( 'wpt_header_body_logo_width', toggle( setting, [ 'enabled' ]) );
	});
}

function toggle( setting, expected ) {
	return function( control ) {

		function isDisplayed() {
			return -1 !== $.inArray( setting.get(), expected );
		}

		function setActive() {
			control.active.set( isDisplayed() );
		}

		control.active.validate = isDisplayed;
		setActive();
		setting.bind( setActive );
	};
}

