<?php

namespace WP_Titan_1_1_1\Setting;

use WP_Titan_1_1_1\App;
use WP_Titan_1_1_1\Core;

defined( 'ABSPATH' ) || exit;

class Setting extends Base {

	protected $type;
	protected $setting;
	protected $box;
	protected $sub_tab;
	protected $tab;
	protected $page;
	protected $key;
	protected $title;
	protected $args;
	protected $required_label;
	protected $sanitize_callback = 'sanitize_text_field';

	protected $default_args = array();

	public function __construct(
		App $app,
		Core $core,
		Storage $storage,
		string $type,
		string $setting,
		string $box,
		?string $sub_tab,
		string $tab,
		string $page,
		?string $title,
		array $args,
		string $required_label
	) {
		parent::__construct( $app, $core, $storage, $page );

		$this->type           = $type;
		$this->setting        = $setting;
		$this->box            = $box;
		$this->sub_tab        = $sub_tab;
		$this->tab            = $tab;
		$this->key            = $storage->get_page_key( $page ) . '_' . $tab . ( $sub_tab ? ( '_' . $sub_tab ) : '' ) . '_' . $box . '_' . $setting;
		$this->title          = $title;
		$this->args           = wp_parse_args( $args, $this->default_args );
		$this->required_label = $required_label;

		if (
			isset( $args['sanitize_callback'] ) && (
			is_array( $args['sanitize_callback'] ) ? (
				2 === count( $args['sanitize_callback'] ) &&
				method_exists( $args['sanitize_callback'][0], $args['sanitize_callback'][1] ) ) :
			function_exists( $args['sanitize_callback'] ) )
		) {
			$this->sanitize_callback = $args['sanitize_callback'];
		}

		add_action(
			'admin_menu',
			function (): void {
				$this->core->hook()->add_action( 'setting_box', array( $this, 'render' ), 10, 4 );
			},
			5
		);
	}

	public function get_key(): string {
		return $this->key;
	}

	public function get_sanitize_callback(): ?string {
		return $this->sanitize_callback;
	}

	public function get() /* mixed */ {
		return get_option( $this->key, isset( $this->args['default'] ) ?? null );
	}

	public function render( string $box, ?string $sub_tab, string $tab, string $page ): void {
		if (
			$this->box !== $box ||
			( $sub_tab && $this->sub_tab !== $sub_tab ) ||
			$this->tab !== $tab ||
			$this->page !== $page
		) {
			return;
		}

		Control::render(
			$this->core,
			$this->type,
			$this->key,
			$this->get(),
			$this->title,
			$this->args,
			$this->required_label,
			$box,
			$sub_tab,
			$tab,
			$page
		);
	}
}
