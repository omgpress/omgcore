<?php

namespace WP_Titan_1_0_19\Setting;

defined( 'ABSPATH' ) || exit;

class Page {

	public function __construct() {
		add_action(
			'admin_menu',
			function () use ( $page, $nav_title, $title ): void {
				$this->page = add_submenu_page(
					'edit.php',
					$title ?? $nav_title,
					$nav_title,
					'delete_posts',
					$this->app->get_key( $page ),
					array( $this, 'render_page' )
				);
			},
			1001
		);
	}
}
