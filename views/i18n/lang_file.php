<?php

echo "<?php defined('SYSPATH') OR die('No direct access allowed.');\n\n";

echo "// I18n generated at: " . date('Y-m-d H:i:s T') . "\n\n";

echo "$lang =  array\n(\n";
// @todo support for multi level arrays
foreach ($langs as $lang)
{
	// Get rid of invalid characters
	$lang->text = str_replace("'", "\'", $lang->text);
	
	echo "\t'".str_replace("'", "\'", $lang->key)."' => '".$lang->text."',\n";
}

echo ");";