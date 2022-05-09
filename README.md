# WordPress Titan

<a href="https://packagist.org/packages/dpripa/wp-titan"><img src="https://img.shields.io/packagist/v/dpripa/wp-titan" alt="Packagist version"/></a>

One entry point to get all you need for developing a WordPress plugin or a theme.\
WP Titan introduces a smart layer between WordPress and your application.\
P.S. It's also easy to start using in a live application.

- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Initialization](#initialization)
- [Documentation](#documentation)
- [Example](#example)
- [License](#license)

## System Requirements
- PHP: ^7.2.0
- WordPress: ^5.0.0
- Composer (optional)

## Installation
Run the following command in root directory of the application to install using Composer:\
`composer require dpripa/wp-titan`.\
Alternatively, you can [download the latest release](https://github.com/dpripa/wp-titan/releases/latest) directly and place unarchived folder in the root directory of the application.

## Initialization
To initialize WP Titan for your application use the following reference code to configure your plugin or theme main file (index.php, functions.php, etc.).
```php
require_once __DIR__ . '/vendor/dpripa/wp-titan/index.php';

// Always be sure that the WP Titan namespace matches the installed version of the library.
// This is because other plugin and theme may use a different version.
// For example, where 'WP_Titan_x_x_x' version is x.x.x.
use WP_Titan_1_0_10\App as App;

// Define a function that returns the singleton instance of WP Titan for your application.
function app(): App {
  return App::get(
    'my_plugin', // Enter the unique key to WP Titan instance as namespace of your application.
    __FILE__ // The main (root) file of your plugin or theme, leave it as is.
  );
}
```

## Documentation
The latest documentation is published on [wpt.dpripa.com](https://wpt.dpripa.com).\
For convenience, it's better to start from [the entry point](https://wpt.dpripa.com/classes/WP-Titan-1-0-10-App.html) of the library.

## Example
The following is a simple example when WP Titan is used in the plugin environment.\
Don't worry, for the theme all is the same. WP Titan auto-detects your application environment and provides a universal API.

#### index.php
```php
/**
 * Plugin Name: My Plugin
 * Plugin URI:  https://wordpress.org
 * Description: Just my plugin.
 * Version:     1.0.0
 * Author:      Some Dude
 * Author URI:  https://wordpress.org
 */

namespace My_Plugin;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/dpripa/wp-titan/index.php';
require_once __DIR__ . '/vendor/autoload.php';

use WP_Titan_1_0_10\App as App;

function app(): App {
  return App::get( 'my_plugin', __FILE__ );
}

new Setup();
```
You can see an example of simpleton usage here. It's a structural pattern for the WordPress based applications provided by WP Titan. Read more about [simpleton](https://wpt.dpripa.com/classes/WP-Titan-1-0-10-Simpleton.html).

#### Setup.php
```php
namespace My_Plugin;

defined( 'ABSPATH' ) || exit;

final class Setup {

  public function __construct() {
    if ( app()->simpleton()->validate( self::class ) ) {
      return;
    }

    app()->i18n()->setup()
      ->admin()->notice()->setup()
      ->add_setup_action( array( $this, 'setup' ) );
  }

  public function setup(): void {
    if ( ! app()->integration()->wc()->is_active() ) {
      app()->admin()->notice()->render(
        app()->i18n()->__( 'My Plugin require WooCommerce.' )
      );

      return;
    }

    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

    new Cart();
    new Checkout();
    // ... other parts of logic.
  }

  public function enqueue_assets(): void {
    app()->asset()->enqueue_style( 'main' )
      ->asset()->enqueue_script( 'main' );
  }
}
```

#### Cart.php
```php
namespace My_Plugin;

defined( 'ABSPATH' ) || exit;

final class Cart {

  public function __construct() {
    if ( app()->simpleton()->validate( self::class ) ) {
      return;
    }

    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
  }

  public static function get_product_limit(): int {
    return get_option( app()->get_key( 'cart_product_limit' ), 100 );
  }

  public function enqueue_assets(): void {
    app()->asset()->enqueue_style( 'cart' )
      ->asset()->enqueue_script( 'cart' );
  }
}
```

## License
WordPress Titan is free library (software), and is released under the terms of the GPL (GNU General Public License) version 2 or (at your option) any later version. See [LICENSE](https://github.com/dpripa/wp-titan/blob/main/LICENSE).
