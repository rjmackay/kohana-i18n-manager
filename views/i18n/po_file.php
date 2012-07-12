#. extracted from <?php echo $pot_source; ?>, <?php echo $source; ?>

msgid ""
msgstr ""
"Project-Id-Version: PACKAGE VERSION\n"
"Report-Msgid-Bugs-To: \n"
"POT-Creation-Date: <?php echo date('Y-m-d H:iO') ?>\n"
"PO-Revision-Date: YEAR-MO-DA HO:MI+ZONE\n"
"MIME-Version: 1.0\n"
"Content-Type: text/plain; charset=UTF-8\n"
"Content-Transfer-Encoding: 8bit\n"
"X-Generator: ushahidi-i18n-manager\n"
<?php if ($language != $pot_language) : ?>
"Language: <?php echo $language ?>\n"
<?php endif; ?>

<?php

// @todo support for multi level arrays
foreach ($pot_messages as $key => $source_val)
{
$val = isset($messages[$key]) ? $messages[$key] : "";
?>
#: <?php echo $key; ?>

msgctxt "<?php echo addslashes($key); ?>"
msgid "<?php echo addcslashes($source_val,"\n\r\t\0\v\f\"\'"); ?>"
msgstr "<?php echo addcslashes($val,"\n\r\t\0\v\f\"\'"); ?>"

<?php
}
