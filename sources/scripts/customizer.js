import 'jquery';
import setupControlAlphaColor from './customizer/controls/alpha-color';
import setupControlNumber from './customizer/controls/number';
import setupSectionLink from './customizer/sections/link';

$( document ).on( 'ready', function() {
	setupControlAlphaColor();
	setupControlNumber();
	setupSectionLink();
});
