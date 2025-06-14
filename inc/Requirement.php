<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class Requirement {
	protected Info $info;
	protected AdminNotice $admin_notice;
	protected array $requirements = array();
	protected string $error_message_single;
	protected string $error_message_plural;

	public function __construct( Info $info, AdminNotice $admin_notice, array $config ) {
		$this->info                 = $info;
		$this->admin_notice         = $admin_notice;
		$this->error_message_single = $config['error_message_single'] ?? '%1$s requires the %2$s plugin.';
		$this->error_message_plural = $config['error_message_plural'] ?? '%1$s requires the following plugins: %2$s.';
	}

	public function add( string $classname_or_filename, string $title ): self {
		$this->requirements[ $classname_or_filename ] = $title;

		return $this;
	}

	public function validate(): bool {
		if ( empty( $this->requirements ) ) {
			return false;
		}

		$plugin_name          = '"' . $this->info->get_name() . '"';
		$missing_requirements = '';

		foreach ( $this->requirements as $classname_or_filename => $title ) {
			if (
				! class_exists( $classname_or_filename ) &&
				! is_plugin_active( $classname_or_filename )
			) {
				$missing_requirements .= $missing_requirements ? ", \"$title\"" : "\"$title\"";
			}
		}

		if ( empty( $missing_requirements ) ) {
			return false;
		}

		$message = sprintf(
			1 < count( $this->requirements ) ?
				$this->error_message_plural :
				$this->error_message_single,
			$plugin_name,
			$missing_requirements
		);

		$this->admin_notice->render( $message, 'error' );

		return true;
	}
}
