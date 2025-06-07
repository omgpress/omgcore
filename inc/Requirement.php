<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class Requirement {
	protected Info $info;
	protected AdminNotice $admin_notice;
	protected array $requirements = array();

	public function __construct( Info $info, AdminNotice $admin_notice ) {
		$this->info         = $info;
		$this->admin_notice = $admin_notice;
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

		$message = 1 < count( $this->requirements ) ?
			__( '%1$s requires the following plugins: %2$s.', $this->info->get_textdomain() ) :
			__( '%1$s requires the %2$s plugin.', $this->info->get_textdomain() );
		$message = sprintf( $message, $plugin_name, $missing_requirements );

		$this->admin_notice->render( $message, 'error' );

		return true;
	}
}
