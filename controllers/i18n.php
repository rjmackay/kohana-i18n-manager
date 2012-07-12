<?php defined('SYSPATH') OR die('No direct access allowed.');

class I18n_Controller extends Controller {
	
	public $source_language = 'en_US';
	
	public function __construct()
	{
		parent::__construct();
		header('Content-type: text/plain');
	}
	
	public function before()
	{
		echo "+ ---------- start ----------- +\n\n";
	}
	
	public function after()
	{
		echo "\n\n+ --------- complete --------- +\n\n";
	}
	
	/**
	 * This generates a language file based off of the Kohana::lang() i18n function
	 * native to Kohana.  It scans your application directory for the
	 * usage of that function, and makes the language file from the key.
	 *
	 * @option  --lang  Language: en, fr, en-us
	 */
	/*
	public function generate()
		{
			$options = $_GET;
			
			if (! isset($options['lang'])) die('You must specify a language to generate with --lang.');
			
			$files = Kohana::list_files(APPPATH, TRUE);
			//$files = Arr::flatten($files);
			var_dump($files);
			
			// Array for the keys
			$data = array();
			
			foreach ($files as $rel => $file)
			{
				preg_match_all('/Kohana::lang\(\'([^\']*)\'\)/is', file_get_contents($file), $matches);
				
				foreach ($matches[1] as $key)
				{
					$class = new stdclass();
					$class->key = $key;
					$class->text = $key;
					
					$data[] = $class;
				}
			}
			
			$content = View::factory('i18n/lang_file')->set('langs', $data);
			
			$this->__write_file($options['lang'], $content);
		}*/
	
	
	/**
	 * This copies keys from one language to another. It will
	 * take the language keys and overwrite any existing ones,
	 * and then write it out to the file again.
	 *
	 * You will lose keys that do not exist in --source
	 *
	 * @option  --source  string  Language en, us, fr-fr
	 * @option  --target  string  Language en, us, fr-fr
	 * @option  --clear  int  Optional: Does not overwrite with translated keys
	 */
	/*
	public function copy_keys()
		{
			$options = CLI::options('source', 'target', 'clear');
			
			// Get subdirectories
			$source = str_replace('-', '/', $options['source']);
			$target = str_replace('-', '/', $options['target']);
			
			$source_file = APPPATH."i18n/$source".EXT;
			$target_file = APPPATH."i18n/$target".EXT;
			
			// Grab the files, unless they dont exist yet
			$source = is_file($source_file) ? include $source_file : array();
			$target = is_file($target_file) ? include $target_file : array();
			
			echo "Keys: [source=".count($source)." target=".count($target)."]\n\n";
			
			// If you do not want to use already translated keys
			if (! isset($options['clear'])) $source = Arr::overwrite($source, $target);
			
			// Format it for the view
			$data_formatted = array();
			
			foreach ($source as $key => $value)
			{
				$class = new stdclass();
				$class->key = $key;
				$class->text = $value;
				
				$data_formatted[] = $class;
			}
			
			$content = View::factory('i18n/lang_file')->set('langs', $data_formatted);
			
			$this->__write_file($options['target'], $content);
		}*/
	
	
	/**
	 * Display which keys are different between sources
	 *
	 * @option  --source  string  Language en, us, fr-fr
	 * @option  --target  string  Language en, us, fr-fr
	 */
	/*
	public function diff_keys()
		{
			$options = CLI::options('source', 'target');
			
			// Get subdirectories
			$source = str_replace('-', '/', $options['source']);
			$target = str_replace('-', '/', $options['target']);
			
			$source_file = APPPATH."i18n/$source".EXT;
			$target_file = APPPATH."i18n/$target".EXT;
			
			// Grab the files, unless they dont exist yet
			$source_data = is_file($source_file) ? include $source_file : array();
			$target_data = is_file($target_file) ? include $target_file : array();
			
			echo "[$source=".count($source_data)." $target=".count($target_data)."]\n\n";
			
			$diff = array_diff_key($source_data, $target_data);
			
			echo "Keys present in '$source' but not in '$target':\n";
			print_r($diff);
		}*/
	
	
	/**
	 * Sync all of the translation files and keys
	 * to the database, any that exist will be ignored.
	 */
	/*
	public function db_import()
		{
			// This will check to see if the table exists
			$model = ORM::factory('translation');
			
			$langs = Kohana::list_files(APPPATH.'i18n');
			//$langs = Arr::flatten($langs);
			
			foreach ($langs as $file => $paths)
			{
				$lang_key = $this->__file_to_lang($file);
				$lang_data = include APPPATH.$file;
				
				foreach ($lang_data as $key => $value)
				{
					// Check to see if it exists
					$exists = (bool) DB::select(DB::expr('COUNT(*) AS Count'))
						->from('translations')
						->where('key', '=', $key)
						->where('language', '=', $lang_key)
						->execute()
						->get('Count');
						
					if (! $exists)
					{
						$model = ORM::factory('translation');
						
						$model->key = $key;
						$model->language = $lang_key;
						$model->text = $value;
						$model->save();
					}
				}
			}
		}*/
	
	
	/**
	 * This will remove all of the files from the APPPATH/i18n
	 * directory and will replace it with what is in the database.
	 * If you are using git/subversion be sure to take into account
	 * checked in files and conflicts.
	 */
	/*
	public function db_export()
		{
			$path = APPPATH.'i18n';
			
			if (! is_writable($path)) throw new Kohana_Exception(':path must be writable', array(':path' => $path));
			
			$model = ORM::factory('translation');
			
			$files = Kohana::list_files(APPPATH.'i18n');
			
			// Remove old files
			foreach ($files as $file => $path) unlink($path);
			
			$langs = DB::select('language')
				->from('translations')
				->group_by('language')
				->execute();
			
			foreach ($langs as $lang)
			{
				$file = $this->__lang_to_file($lang['language']);
				
				$langs = $model->where('language', '=', $lang['language'])->find_all();
				
				$content = View::factory('i18n/lang_file')->set('langs', $langs);
			
				$this->__write_file($lang['language'], $content);
			}
		}*/
	
	
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
				if ($entry == '.' OR $entry == '..' OR $entry == 'po')
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
				// Load translations
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
				// If this is the source language just use a blank array
				else
				{
					$messages = array();
				}
				
				$content = new View('i18n/po_file');
				$content->messages = $messages;
				$content->pot_messages = $pot;
				$content->source = $language.'/'.$group.'.php';
				$content->language = $language;
				$content->group = $group;
				$content->pot_language = $this->source_language;
				
				$this->__write_po_file($language,$group,$content);
			}
		}
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
		
		if (!is_dir(APPPATH."i18n/po/$lang/"))
		{
			mkdir(APPPATH."i18n/po/$lang/");
		}
		
		// Write the contents to the file
		$file = APPPATH."i18n/po/$lang/$file.po";
		
		file_put_contents($file, $content);
		
		//chmod($file, 0755);
	}
	
	private function __write_file($lang, $file, $content)
	{
		$content = mb_convert_encoding($content, 'UTF-8');
		
		// Write the contents to the file
		$file = APPPATH.$this->__lang_to_file($lang, $file);
		
		file_put_contents($file, $content);
		
		chmod($file, 0755);
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