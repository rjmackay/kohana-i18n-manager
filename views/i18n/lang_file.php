<?php
echo "<?php defined('SYSPATH') OR die('No direct access allowed.');\n";

echo "// I18n generated at: " . date('Y-m-d H:iO') . "\n";
echo "// PO revision date: " . $header['PO-Revision-Date'] . "\n\n";

echo '$lang = ';
var_export($lang);
echo ";\n\n";
