# WordPress Titan (WPT)
### Foundation of your WordPress project
The library solves the issue of isolating all assets of your WordPress plugin or theme, provides a collection of useful methods and extensions, as well as a single interface for a plugin and theme.

### Requirements
- PHP 7.2+
- Composer
- WordPress 5.0+


## Installing and initialization
- Install library using `composer require dpripa/wp-titan`.
- It's recommended to use the PHP namespace throughout your application, including the main file (index.php, functions.php, etc.) and templates.
- Place this code at the beginning of your plugin or theme main file:
```php
require_once dirname( __FILE__ ) . '/vendor/dpripa/wp-titan/index.php';

// Always be sure that the WPT namespace matches the installed version of the library.
// This is because other plugins and themes might use the different versions.
// For example, where 'WP_Titan_x_x_x' version is x.x.x.
use WP_Titan_0_9_0\App as WP_Titan;

function wpt(): WP_Titan {
	return WP_Titan::get_instance(
		'my_wp_project', // The key to WPT instance as namespace of your plugin or theme.
		__FILE__ // The main (root) file of your plugin or theme.
	);
}
```
- Completed!

## Documentation
- Call functionality using `wpt()` object methods.

_Full documentation will be published with version 1.0.0._

## License

WordPress Titan is free software, and is released under the terms of the GPL (GNU General Public License) version 2 or (at your option) any later version. See [LICENSE](https://github.com/dpripa/wp-titan/blob/main/LICENSE).
