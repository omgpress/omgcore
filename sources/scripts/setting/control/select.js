import 'select2';

export default function initControlSelect() {
	const $multiple = $( '.wpt-control-select select[multiple]' );

	if ( $multiple.length ) {
		$multiple.select2();
	}
}
