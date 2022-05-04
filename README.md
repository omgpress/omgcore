# WordPress Titan
One entry point to get all your needs for developing a WordPress plugin or a theme. WP Titan introduces a smart layer between WordPress and your project.\
P.S. It's also easy to start using in a live project.

## Getting Started

### System Requirements
- PHP 7.2+
- WordPress 5.0+
- Composer (optional)

### Installation
Run the following command in root directory of the project to install using Composer: `composer require dpripa/wp-titan`.\
Alternatively, you can [download the latest version](https://github.com/dpripa/wp-titan/releases) directly and place unarchived folder in the root directory of the project.

### Initialization
To initialize WP Titan for your project use the following reference code to configure your plugin or theme main file (index.php, functions.php, etc.).
```php
require_once dirname( __FILE__ ) . '/vendor/dpripa/wp-titan/index.php';

// Always be sure that the WP Titan namespace matches the installed version of the library.
// This is because other plugins and themes might use the different versions.
// For example, where 'WP_Titan_x_x_x' version is x.x.x.
use WP_Titan_1_0_0\App as WP_Titan;

// Define a function that returns the singleton instance of WP Titan for your project.
function wpt(): WP_Titan {
  return WP_Titan::get_project(
    'my_project', // Enter the unique key to WP Titan instance as namespace of your plugin or theme.
    __FILE__ // The main (root) file of your plugin or theme, leave it as is.
  );
}
```

### [Documentation](https://wpt.dpripa.com)
The latest documentation is published on [wpt.dpripa.com](https://wpt.dpripa.com).\
For convenience, it's better to start from [the entry point](https://wpt.dpripa.com/classes/WP-Titan-1-0-0-App.html) of the library.

### Examples
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

require_once dirname( __FILE__ ) . '/vendor/dpripa/wp-titan/index.php';

use WP_Titan_1_0_0\App as WP_Titan;

function wpt(): WP_Titan {
  return WP_Titan::get_project(
    'my_project',
    __FILE__
  );
}

// Composer autoloader.
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

new Setup();
```

#### Setup.php
```php
namespace My_Project;

defined( 'ABSPATH' ) || exit;

final class Setup {

  public function __construct() {
    if ( wpt()->simpleton()->validate( self::class ) ) {
      return;
    }

    add_action( 'plugins_loaded', array( $this, 'setup' ) );

    // One difference, for the theme we use:
    // add_action( 'after_setup_theme', array( $this, 'setup' ) );
  }

  public function setup(): void {
    wpt()->i18n()->setup();
    wpt()->admin()->notice()->setup();

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
    wpt()->asset()
      ->enqueue_style( 'main' )
      ->enqueue_script( 'main' );
  }
}
```

## License
WordPress Titan is free library (software), and is released under the terms of the GPL (GNU General Public License) version 2 or (at your option) any later version. See [LICENSE](https://github.com/dpripa/wp-titan/blob/main/LICENSE).
