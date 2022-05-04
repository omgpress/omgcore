import { validateComponentInit } from '../../helpers/component';

export default function initSectionLink() {
	if ( validateComponentInit( 'section-link' ) ) {
		return;
	}

	wp.customize.sectionConstructor['wpt_link'] = wp.customize.Section.extend({
		attachEvents: function() {},
		isContextuallyActive: function() {
			return true;
		}
	});
}
