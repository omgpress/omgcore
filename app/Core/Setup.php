<?php

namespace WP_Titan_0_9_2\Core;

use WP_Titan_0_9_2\App;
use WP_Titan_0_9_2\Core;
use WP_Titan_0_9_2\Feature;

defined( 'ABSPATH' ) || exit;

final class Setup extends Feature {

	protected $feature_key = 'setup';

	protected $priority           = 999;
	protected $admin_notice       = false;
	protected $debug              = false;
	protected $customizer_control = false;

	protected $config_keys = array(
		'priority',
		'admin_notice',
		'debug',
		'customizer_control',
	);

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		if ( $this->app->simpleton()->has_instance( self::class ) ) {
			return;
		}

		$setup_action = 'theme' === $this->app->get_env() ? 'after_setup_theme' : 'plugin_loaded';

		add_action( $setup_action, array( $this, 'setup' ), $this->priority );
	}

	public function setup() {
		if ( $this->admin_notice ) {
			add_action( 'admin_init', array( $this->core->admin()->notice(), 'action_render_transients' ), $this->priority );
		}

		if ( $this->debug ) {
			add_action( 'admin_init', array( $this->core->debug(), 'action_delete_log_by_url' ), $this->priority );
		}

//		if ( $this->customizer_control ) {
//			add_action( 'customize_controls_enqueue_scripts', array( $this->core->customizer()->control(), 'action_enqueue_assets' ), $this->priority );
//		}
	}
}
