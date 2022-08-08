import { validateComponentSetup } from '../../helpers/component';

export default function setupSectionLink() {
	if ( validateComponentSetup( 'section-link' ) ) {
		return;
	}

	wp.customize.sectionConstructor['wpappy_link'] = wp.customize.Section.extend({
		attachEvents: function() {},
		isContextuallyActive: function() {
			return true;
		}
	});
}
