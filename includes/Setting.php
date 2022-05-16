<?php

namespace WP_Titan_1_0_19;

defined( 'ABSPATH' ) || exit;

/**
 * Manage settings.
 */
class Setting extends Feature {

	protected $pages    = array();
	protected $tabs     = array();
	protected $sub_tabs = array();
	protected $boxes    = array();
	protected $settings = array();

	protected $master_scope;
	protected $scope;

	public function add_page( string $page, string $nav_title, string $title = '' ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$this->master_scope['page'] = $page;

		$this->pages[ $page ] = new Setting\Page();

		return $this->app;
	}

	public function add_tab( string $tab, string $nav_title, string $title = '' ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$this->master_scope['tab'] = $tab;

		$this->tabs[ $tab ] = new Setting\Tab();

		return $this->app;
	}

	public function add_sub_tab( string $sub_tab, string $nav_title, string $title = '' ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$this->master_scope['sub_tab'] = $sub_tab;

		$this->sub_tabs[ $sub_tab ] = new Setting\Sub_Tab();

		return $this->app;
	}

	public function add_box( string $box, string $title = '' ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$this->master_scope['box'] = $box;

		$this->boxes[ $box ] = new Setting\Box();

		return $this->app;
	}

	public function add( string $setting, array $args ): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		$this->settings[ $setting ] = new Setting\Setting();

		return $this->app;
	}

	public function get( string $setting, ?string $page = null, ?string $tab = null, ?string $sub_tab_or_box = null, ?string $box = null ) /* mixed */ {
		if ( $this->validate_setup() ) {
			return null;
		}

		return get_option();
	}

	public function start_scope(): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		return $this->app;
	}

	public function end_scope(): App {
		if ( $this->validate_setup() ) {
			return $this->app;
		}

		return $this->app;
	}

	protected function scope(): Setting\Scope {
		return $this->get_feature( $this->app, $this->core, 'scope', Setting\Scope::class );
	}

	protected function master_scope(): Setting\Scope {
		return $this->get_feature( $this->app, $this->core, 'master_scope', Setting\Scope::class );
	}

	public function setup(): App {
		$this->add_setup_action(
			__FUNCTION__,
			function (): void {
				$this->enqueue_assets();
			}
		);

		return $this->app;
	}

	protected function enqueue_assets(): void {
		add_action(
			'admin_enqueue_scripts',
			function (): void {
				$this->core->asset()->enqueue_script( 'setting' );
				$this->core->asset()->enqueue_style( 'setting' );
			}
		);
	}
}
