# Initialize

### In plugin environment

```php
namespace Example_Plugin;

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/vendor/dpripa/wp-titan/index.php';

function wpt(): \WP_Titan\App {
	return \WP_Titan\App::get_instance( 'example_plugin' );
}

wpt()->http->set_root_file( __FILE__ );
```

### In theme environment

```php
namespace Example_Theme;

defined( 'ABSPATH' ) || exit;

require_once dirname( __FILE__ ) . '/vendor/dpripa/wp-titan/index.php';

function wpt(): \WP_Titan\App {
	return \WP_Titan\App::get_instance( 'example_theme', 'theme' );
}
```
