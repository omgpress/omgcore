# WordPress Titan

<a href="https://packagist.org/packages/dpripa/wp-titan"><img src="https://img.shields.io/packagist/v/dpripa/wp-titan" alt="Packagist version"/></a>

One entry point to get all you need for developing a WordPress plugin or a theme.\
WP Titan introduces a smart layer between WordPress and your project.\
P.S. It's also easy to start using in a live project.

- [Getting Started](#getting-started)
  - [System Requirements](#system-requirements)
  - [Installation](#installation)
  - [Initialization](#initialization)
  - [Documentation](#documentation)
  - [Example](#example)
- [Changelog](#changelog)
- [License](#license)

## Getting Started

### System Requirements
- PHP 7.2+
- WordPress 5.0+
- Composer (optional)

### Installation
Run the following command in root directory of the project to install using Composer:\
`composer require dpripa/wp-titan`.\
Alternatively, you can [download the latest version](https://github.com/dpripa/wp-titan/releases) directly and place unarchived folder in the root directory of the project.

### Initialization
To initialize WP Titan for your project use the following reference code to configure your plugin or theme main file (index.php, functions.php, etc.).
```php
require_once __DIR__ . '/vendor/dpripa/wp-titan/index.php';

// Always be sure that the WP Titan namespace matches the installed version of the library.
// This is because other plugin and theme may use a different version.
// For example, where 'WP_Titan_x_x_x' version is x.x.x.
use WP_Titan_1_0_3\App as WP_Titan;

// Define a function that returns the singleton instance of WP Titan for your project.
function wpt(): WP_Titan {
  return WP_Titan::get_instance(
    'my_project', // Enter the unique key to WP Titan instance as namespace of your plugin or theme.
    __FILE__ // The main (root) file of your plugin or theme, leave it as is.
  );
}
```

### Documentation
The latest documentation is published on [wpt.dpripa.com](https://wpt.dpripa.com).\
For convenience, it's better to start from [the entry point](https://wpt.dpripa.com/classes/WP-Titan-1-0-3-App.html) of the library.

### Example
The following is a simple example when WP Titan is used in the plugin environment.\
Don't worry, for the theme all is the same. WP Titan auto-detects your project environment and provides a universal API.

#### index.php
```php
/**
 * Plugin Name: My Project
 * Plugin URI:  https://example.com
 * Description: Plugin for tests
 * Version:     1.0.0
 * Author:      Some Dude
 * Author URI:  https://example.com
 */

namespace My_Project;

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/vendor/dpripa/wp-titan/index.php';

use WP_Titan_1_0_3\App as WP_Titan;

function wpt(): WP_Titan {
  return WP_Titan::get_instance( 'my_project', __FILE__ );
}

// Composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

new Setup();
```
You can see an example of simpleton usage here. It's a structural pattern for WordPress projects provided by WP Titan. Read more about [simpleton](https://wpt.dpripa.com/classes/WP-Titan-1-0-3-Simpleton.html).

#### Setup.php
```php
namespace My_Project;

defined( 'ABSPATH' ) || exit;

final class Setup {

  public function __construct() {
    if ( wpt()->simpleton()->validate( self::class ) ) {
      return;
    }

    wpt()->i18n()->setup()
      ->admin()->notice()->setup();

    add_action( 'plugins_loaded', array( $this, 'setup' ) );

    // Only one difference, for the theme we use:
    // add_action( 'after_setup_theme', array( $this, 'setup' ) );
  }

  public function setup(): void {
    if ( ! wpt()->integration()->wc()->is_active() ) {
      wpt()->admin()->notice()->render(
        wpt()->i18n()->__( 'My Project required WooCommerce.' )
      );

      return;
    }

    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

    new Cart();
    new Checkout();
    // ... other parts of logic.
  }

  public function enqueue_assets(): void {
    wpt()->asset()->enqueue_style( 'main' )
      ->asset()->enqueue_script( 'main' );
  }
}
```

#### Cart.php
```php
namespace My_Project;

defined( 'ABSPATH' ) || exit;

final class Cart {

  public function __construct() {
    if ( wpt()->simpleton()->validate( self::class ) ) {
      return;
    }

    add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
  }

  public static function get_product_limit(): int {
    return get_option( wpt()->get_key( 'cart_product_limit' ), 100 );
  }

  public function enqueue_assets(): void {
    wpt()->asset()->enqueue_style( 'cart' )
      ->asset()->enqueue_script( 'cart' );
  }
}
```

## Changelog

#### 1.0.3
- Improved all `::setup()` methods.
- Improved `Asset` and `FS` features.
- Improved documentation.

#### 1.0.2
- Improved `Asset` and `Upload` features.
- Improved `App::get_key()` method.

#### 1.0.1
- Added verification of feature setup.
- Improved `I18n` and `Asset` features.
- Improved documentation.

#### 1.0.0
- Released first stable version.

## License
WordPress Titan is free library (software), and is released under the terms of the GPL (GNU General Public License) version 2 or (at your option) any later version. See [LICENSE](https://github.com/dpripa/wp-titan/blob/main/LICENSE).
