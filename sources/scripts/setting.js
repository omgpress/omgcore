import 'jquery';
import setupNotices from './setting/notices';
import setupPage from './setting/page';
import setupControlSelect from './setting/control/select';

$( document ).on( 'ready', function() {
	setupNotices();
	setupPage();
	setupControlSelect();
});
