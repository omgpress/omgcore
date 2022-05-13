<?php

namespace WP_Titan_1_0_18;

defined( 'ABSPATH' ) || exit;

/**
 * Information from the application metadata.
 */
class Info extends Feature {

	protected $name;
	protected $url;
	protected $version;
	protected $description;
	protected $author;
	protected $author_url;
	protected $textdomain;
	protected $domain_path;
	protected $requires_php;
	protected $requires_wp;

	protected $headers = array(
		'version'      => 'Version',
		'description'  => 'Description',
		'author'       => 'Author',
		'author_url'   => 'Author URI',
		'textdomain'   => 'Text Domain',
		'domain_path'  => 'Domain Path',
		'requires_wp'  => 'Requires at least',
		'requires_php' => 'Requires PHP',
	);

	public function __construct( App $app, Core $core ) {
		parent::__construct( $app, $core );

		if ( $this->is_theme() ) {
			$info = $this->get_theme_data();

		} else {
			$info = $this->get_plugin_data();
		}

		$this->name         = $info['name'];
		$this->url          = $info['url'];
		$this->version      = $info['version'];
		$this->description  = $info['description'];
		$this->author       = $info['author'];
		$this->author_url   = $info['author_url'];
		$this->textdomain   = $info['textdomain'];
		$this->domain_path  = $info['domain_path'];
		$this->requires_php = $info['requires_wp'];
		$this->requires_wp  = $info['requires_php'];
	}

	public function get_name(): string {
		return $this->name;
	}

	public function get_url(): string {
		return $this->name;
	}

	public function get_version(): string {
		return $this->version;
	}

	public function get_description(): string {
		return $this->description;
	}

	public function get_author(): string {
		return $this->author;
	}

	public function get_author_url(): string {
		return $this->author_url;
	}

	public function get_textdomain(): string {
		return $this->textdomain;
	}

	public function get_domain_path(): string {
		return $this->domain_path;
	}

	public function get_requires_php(): string {
		return $this->requires_php;
	}

	public function get_requires_wp(): string {
		return $this->requires_wp;
	}

	protected function get_plugin_data(): array {
		$this->headers['name'] = 'Plugin Name';
		$this->headers['url']  = 'Plugin URI';

		return get_file_data(
			$this->app->get_root_file(),
			$this->headers
		);
	}

	protected function get_theme_data(): array {
		$this->headers['name'] = 'Theme Name';
		$this->headers['url']  = 'Theme URI';

		return get_file_data(
			$this->app->fs()->get_path( 'style.css' ),
			$this->headers
		);
	}
}
