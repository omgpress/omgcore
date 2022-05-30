# WordPress Titan

<a href="https://packagist.org/packages/dpripa/wp-titan"><img src="https://img.shields.io/packagist/v/dpripa/wp-titan" alt="Packagist version"/></a>

One entry point to get all you need for developing a WordPress plugin or a theme.\
WP Titan introduces a smart layer between WordPress and your application.\
P.S. It's also easy to start using in a live application.

- [System Requirements](#system-requirements)
- [Installation](#installation)
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

## Documentation
The latest documentation is published on [wpt.dpripa.com](https://wpt.dpripa.com).\
For convenience, it's better to start from [the entry point](https://wpt.dpripa.com/classes/WP-Titan-1-1-1-App.html) of the library.

## Example
The following is a simple example when WP Titan is used. It doesn't matter which environment (plugin or theme) you run this code in, WP Titan automatically detects your app's environment and provides a universal API.

#### Root File (index.php / functions.php)
```php
namespace My_App;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/dpripa/wp-titan/index.php';
require_once __DIR__ . '/vendor/autoload.php';

// Always be sure that the WP Titan namespace matches the installed version of the library.
// This is because other plugin and theme may use a different version.
// For example, where 'WP_Titan_x_x_x' version is x.x.x.
use WP_Titan_1_1_1\App as App;

// Define a function that returns the singleton instance of WP Titan for your application.
function app(): App {
  return App::get( __NAMESPACE__, __FILE__ );
}

new Setup();
```
You can see an example of simpleton usage here. It's a structural pattern provided by WP Titan for the WordPress based applications. Read more about [simpleton](https://wpt.dpripa.com/classes/WP-Titan-1-1-1-Simpleton.html).

#### Setup.php
```php
namespace My_App;

defined( 'ABSPATH' ) || exit;

final class Setup {

  public function __construct() {
    if ( app()->simpleton()->validate( self::class ) ) {
      return;
    }

    app()->i18n()->setup()
      ->admin()->notice()->setup()
      ->setup( array( $this, 'setup' ) );

    // The simpleton classes can be called earlier if necessary for the application logic.
    // For example, it could be database initialization logic:
    // new DB();
  }

  public function setup(): void {
    if ( ! app()->integration()->wc()->is_active() ) {
      app()->admin()->notice()->render(
        app()->i18n()->__( 'My App require WooCommerce.' )
      );

      return;
    }

    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

    // In most cases we call simpleton classes inside the setup action, when the WordPress environment is fully loaded.
    new Cart();
    // new Checkout();
  }

  public function enqueue_assets(): void {
    app()->asset()->enqueue_style( 'main' )
      ->asset()->enqueue_script( 'main' );
  }
}
```

#### Cart.php
```php
namespace My_App;

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
