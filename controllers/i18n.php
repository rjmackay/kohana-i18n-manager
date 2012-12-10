<?php defined('SYSPATH') OR die('No direct access allowed.');

class I18n_Controller extends Controller {
	
	public $source_language = 'en_US';
	
	public $string_escape = "\\\n\r\t\0\v\f\"";
	
	public function __construct()
	{
		parent::__construct();
		header('Content-type: text/plain');
		echo "+ ---------- start ----------- +\n\n";
	}
	
	public function after()
	{
		echo "\n\n+ --------- complete --------- +\n\n";
	}
	
	/**
	 * Generate a po file from the 
	 */
	public function php2po()
	{
		$options = $_GET;
		
		// Get languages
		if (isset($options['lang']))
		{
			if (is_dir(APPPATH.'i18n/'.$options['lang']))
			{
				$languages = array($options['lang']);
			}
			else
			{
				die('Language "'.$options['lang'].'" not present');
			}
		}
		else
		{
			$i18ndir =  dir(APPPATH.'i18n/');
			$languages = array();
			while(false !== ($entry = $i18ndir->read()))
			{
				if ($entry == '.' OR $entry == '..' OR $entry == 'po' OR $entry == 'tools' OR substr($entry,0,1) == '.')
					continue;
				
				if (is_dir(APPPATH.'i18n/'.$entry))
					$languages[] = $entry;
			}
		}
		
		// Get source language files
		$pot_files = array();
		if (isset($options['group']))
		{
			$path = APPPATH."i18n/{$this->source_language}/{$options['group']}.php";
			if (file_exists($path))
			{
				$pot_files[] = $path;
			}
			//$pot_files = array(Kohana::find_file('i18n/'.$this->source_language, $options['group']));
		}
		else
		{
			$i18ndir =  dir(APPPATH.'i18n/'.$this->source_language);
			while(false !== ($entry = $i18ndir->read()))
			{
				if ($entry == '.' OR $entry == '..')
					continue;
				
				if (substr($entry, -4, 4) == '.php')
					$pot_files[] = APPPATH.'i18n/'.$this->source_language.'/'.$entry;
			}
			//$pot_files = Kohana::list_files('i18n/'.$this->source_language);
		}
		
		// Get source translations
		foreach ($pot_files as $path)
		{
			$group = pathinfo($path, PATHINFO_FILENAME);
			include $path;

			// Merge in configuration
			if ( ! empty($lang) AND is_array($lang))
			{
				$pot_messages[$group] = $this->__collapse_lang_array($lang, $group);
			}
			
			unset($lang);
		}

		// 
		foreach ($languages as $language)
		{
			foreach ($pot_messages as $group => $pot)
			{
				// Load translations (only if not source lang)
				$messages = array();
				if (strtolower($language) != strtolower($this->source_language))
				{
					//if ($path = Kohana::find_file('i18n/'.$language, $group))
					$path = APPPATH."i18n/$language/$group.php";
					if ( file_exists($path) )
					{
						include $path;
					
						// Merge in configuration
						if ( ! empty($lang) AND is_array($lang))
						{
							$messages = $this->__collapse_lang_array($lang, $group);
						}
					}
				}
				
				$content = new View('i18n/po_file');
				$content->messages = $messages;
				$content->pot_messages = $pot;
				$content->source = $language.'/'.$group.'.php';
				$content->pot_source = $this->source_language.'/'.$group.'.php';
				$content->language = $language;
				$content->group = $group;
				$content->pot_language = $this->source_language;
				
				echo sprintf("Generate po file for %s : %s\n", $language, $group);
				$this->__write_po_file($language, $group, $content);
			}
		}
		$this->after();
	}
	
	private function __collapse_lang_array($lang, $prefix = '')
	{
		$output = array();
		foreach ($lang as $k => $v)
		{
			if (is_array($v))
			{
				$output = array_merge($output, $this->__collapse_lang_array($v, "$prefix.$k"));
			}
			else
			{
				$output["$prefix.$k"] = $v;
			}
		}
		
		return $output;
	}
	
	private function __write_po_file($lang, $file, $content)
	{
		$content = mb_convert_encoding((string)$content, 'UTF-8');
		$dir = APPPATH."i18n/po/po-$lang/";
		
		if (!is_dir($dir))
		{
			mkdir($dir,0777,TRUE);
		}
		
		// Write the contents to the file
		$ext = ($lang == $this->source_language) ? 'pot' : 'po';
		
		$file = "$dir$file.$ext";
		
		file_put_contents($file, $content);
		
		//chmod($file, 0755);
	}
	
	/**
	 * Generate a php file from the php
	 */
	public function po2php()
	{
		$options = $_GET;
		
		// Get languages
		if (isset($options['lang']))
		{
			if (is_dir(APPPATH.'i18n/po/po-'.$options['lang']))
			{
				$languages = array($options['lang']);
			}
			else
			{
				die('Language "'.$options['lang'].'" not present');
			}
		}
		else
		{
			$i18ndir =  dir(APPPATH.'i18n/po/');
			$languages = array();
			while(false !== ($entry = $i18ndir->read()))
			{
				if ($entry == '.' OR $entry == '..' OR substr($entry,0,3) != 'po-' OR substr($entry,0,1) == '.')
					continue;
				
				if (is_dir(APPPATH.'i18n/po/'.$entry))
					$languages[] = str_replace('po-','',$entry);
			}
		}

		$parser = new POParser();

		// 
		foreach ($languages as $language)
		{
			if (strtolower($language) == strtolower($this->source_language)) continue;
			
			// Get language files
			$files = array();
			if (isset($options['group']))
			{
				$path = APPPATH."i18n/po/po-{$language}/{$options['group']}.po";
				if (file_exists($path))
				{
					$files[] = $path;
				}
			}
			else
			{
				$i18ndir =  dir(APPPATH.'i18n/po/po-'.$language);
				while(false !== ($entry = $i18ndir->read()))
				{
					if ($entry == '.' OR $entry == '..')
						continue;
					
					if (substr($entry, -3, 3) == '.po')
						$files[] = APPPATH.'i18n/po/po-'.$language.'/'.$entry;
				}
			}
			
			// Get source translations
			foreach ($files as $path)
			{
				$group = pathinfo($path, PATHINFO_FILENAME);
				
				list($header, $entries) = $parser->parse($path);
				
				$lang = $this->__build_lang_array($entries, $group);
				
				$content = new View('i18n/lang_file');
				$content->header = $header;
				$content->lang = $lang;
				$content->language = $language;
				$content->group = $group;
				
				//echo $content;
				$this->__write_php_file($language, $group, $content);
				echo sprintf("Generate php lang file for %s : %s\n", $language, $group);
			}
		}
		$this->after();
	}

	private function __build_lang_array($entries)
	{
		$output = array();
		foreach ($entries as $entry)
		{
			$k = $entry['references'][0];
			$v = $entry['msgstr'];
			
			if ($v == '') continue;

			$k = explode('.',$k);
			array_shift($k);
			
			if (count($k) == 0) continue;
			
			if (count($k) == 1)
			{
				$output[$k[0]] = stripcslashes($v);
			}
			else
			{
				$parent = &$output;
				for ($i = 0; $i < count($k)-1; $i++)
				{
					if (!isset($parent[$k[$i]]))
						$parent[$k[$i]] = array();
					
					if (!is_array($parent[$k[$i]]))
						break;
					
					$parent = &$parent[$k[$i]];
				}
				$parent[$k[count($k)-1]] = stripcslashes($v);
			}
			
		}
		
		return $output;
	}
	
	private function __write_php_file($lang, $file, $content)
	{
		$content = (string)$content;
		
		require_once 'PHP/Beautifier.php';
		$oBeautifier = new PHP_Beautifier();
		$oBeautifier->addFilter('ArrayNested');
		$oBeautifier->setIndentChar("\t");
		$oBeautifier->setIndentNumber(1);
		$oBeautifier->setNewLine("\n");
		$oBeautifier->setInputString($content);
		$oBeautifier->process(); // required
		
		$content = $oBeautifier->get();
		
		$content = mb_convert_encoding((string)$content, 'UTF-8');
		$dir = APPPATH."i18n/$lang/";
		
		if (!is_dir($dir))
		{
			mkdir($dir,0777,TRUE);
		}
		
		// Write the contents to the file
		$file = "$dir$file.php";
		
		file_put_contents($file, $content);
		
		//chmod($file, 0755);
	}
	
	private function __file_to_lang($file)
	{
		$key_file = str_replace(EXT, '', $file);
		$key_parts = explode('/', $key_file);
		
		array_shift($key_parts);
		
		return implode('-', $key_parts);
	}
	
	private function __lang_to_file($lang, $file)
	{
		return 'i18n/'.$lang.'/'.$file.EXT;
	}
	
}