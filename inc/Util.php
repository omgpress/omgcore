<?php
namespace OmgCore;

defined( 'ABSPATH' ) || exit;

class Util extends OmgFeature {
	use Util\ArrayInsertToPosition;
	use Util\ConvertIso8601ToMin;
	use Util\DashToCamelcase;
	use Util\GenerateRandom;
	use Util\TruncateHtmlContent;
}
