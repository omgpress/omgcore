# WordPress Titan (WPT)
### Foundation of your WordPress application
The library solves the issue of isolating all assets of your WordPress application, provides a collection of useful methods and extensions, as well as a single interface for a theme and plugin.

## Installing and initialization
- Install library using `composer require dpripa/wp-titan`.
- It's recommended to use the PHP namespace throughout your application, including the main file (index.php, functions.php, etc.) and templates.
- Place this code at the beginning of your plugin or theme main file:
```php
require_once dirname( __FILE__ ) . '/vendor/dpripa/wp-titan/index.php';

// Always be sure that the WPT namespace matches the installed version of the library.
// This is because other plugins and themes might use the different versions.
// For example, where 'WP_Titan_x_x_x' version is x.x.x.
use WP_Titan_1_0_0\App as WP_Titan;

function wpt(): WP_Titan {
	return WP_Titan::get_instance(
		'your_app_name' // The key to WPT instance as namespace of your plugin or theme.
		// 'theme' - can be used as second argument to change the environment. 'plugin' is default.
	);
}

wpt()->fs->set_root_file( __FILE__ ); // Only needed for the plugin environment.
```
- Completed!
- Call functionality using `wpt()` object public properties.
