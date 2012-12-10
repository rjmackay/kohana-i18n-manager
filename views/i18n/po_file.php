#. extracted from <?php echo $pot_source; ?>, <?php echo $source; ?>
#
# Translators: 
msgid ""
msgstr ""
"Project-Id-Version: Ushahidi-Localizations\n"
"Report-Msgid-Bugs-To: http://github.com/ushahidi/Ushahidi-Localizations/issues\n"
"POT-Creation-Date: <?php echo date('Y-m-d H:iO') ?>\n"
<?php if ($language == $pot_language) : ?>
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\n"
<?php else: ?>
"PO-Revision-Date: <?php echo date('Y-m-d H:iO') ?>\n"
<?php endif; ?>
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
<?php if ($language != $pot_language) : ?>
"Language: <?php echo $language ?>\n"
<?php endif; ?>
"X-Generator: ushahidi-i18n-manager\n"
<?php

// @todo support for multi level arrays
foreach ($pot_messages as $key => $source_val)
{
	$val = isset($messages[$key]) ? $messages[$key] : "";
	
	// Strip out strings that are just copy/paste from en_US
	if ($source_val == $val)
	{
		$val = "";
	}
?>

#: <?php echo $key; ?>

msgctxt "<?php echo addslashes($key); ?>"
msgid "<?php echo addcslashes($source_val, $this->string_escape); ?>"
msgstr "<?php echo addcslashes(str_replace("\r\n","\n",$val), $this->string_escape); ?>"
<?php
}
