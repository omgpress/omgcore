<?php

namespace Wpappy_1_0_6;

defined( 'ABSPATH' ) || exit;

/**
 * Manage navigation menus.
 */
class Nav_Menu extends Feature {

	public function add( string $slug, string $title ): App {
		register_nav_menus( array( $this->app->get_key( $slug ) => $title ) );

		return $this->app;
	}

	public function render( string $slug, array $args, ?string $before = null, ?string $after = null ): App {
		$key = $this->app->get_key( $slug );

		if ( ! has_nav_menu( $key ) ) {
			$this->core->debug()->die( "The <code>'$slug'</code> nav menu needs to be added to the application setup." );

			return $this->app;
		}

		$args = wp_parse_args(
			$args,
			array(
				'theme_location' => $key,
				'container'      => 'ul'
			)
		);

		if ( $before ) {
			echo $before;
		}

		wp_nav_menu( $args );

		if ( $after ) {
			echo $after;
		}

		return $this->app;
	}
}
