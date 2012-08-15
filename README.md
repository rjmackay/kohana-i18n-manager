# Kohana 2.x Internationalization Module
<!--
**Internationalization can be a pain**: send translation files off to different people, import them, consolidate them, copy keys between files, add keys to the language files, etc-- this module eases some of those pains.

## How to use:

Here are a few quick examples to get you started in managing your localizations.  This is actually built into Kohana's command line interpreter.

### Generating a language file

This goes through your application directory and looks for the localizing helper function (found in system/base.php).  It generates a language file based off of all the occurrences it finds.

		php index.php "i18n/generate?lang=en"

### Copying keys between files

You have one language file, and you need to update the other language file because you added / removed keys.  This will take the source file and overwrite it with values of the target file, and output the final result.

		php index.php "i18n/copy_keys?source=en&target=fr"
		
### Diff between language files

Find out which keys exist in the source, but not in the target file

		php index.php "i18n/diff_keys?source=en&target=fr"

### Copy language files to a database

Assuming you already have a database configured, it will automatically generate the table (if it does not already exist) and put the language keys (from all language files) into the database.

		php index.php "i18n/db_import"

### Export database data to files

This will remove all of the files from the APPPATH/i18n directory and will replace it with what is in the database. NOTE: If you are using source control, be sure to take into account checked in files and conflicts.

		php index.php "i18n/db_export"
-->		
### Convert php language files to po files


		php index.php "i18n/php2po?lang=en_US&group=ui_main"

### Convert po language files to php files


		php index.php "i18n/po2php?lang=en_US&group=ui_main""

### Requirements

* A functioning Ushahidi install (this should installed in modules/)
* PHP Beautifier (for formatting PHP output). Install as follows:

		sudo pear install --alldeps channel://pear.php.net/php_beautifier-0.1.15
