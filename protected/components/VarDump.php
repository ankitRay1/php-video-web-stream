<?php
class VarDump extends CVarDumper {
	public static function _($var,$depth=10,$highlight=true){
		echo '<pre>';
		echo self::dumpAsString($var,$depth,$highlight);
		echo '</pre>';
	}
}