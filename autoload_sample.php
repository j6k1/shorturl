<?php
	namespace Todays\Libs;
	
	spl_autoload_register(function ($class) {
		// deal with funny is_callable('static::classname') side-effect
		if (strpos($class, 'static::') === 0)
		{
			return true;
		}
		
		$class = ltrim($class, '\\');
		
		if(strpos($class, 'Todays\\Sample\\ShortUrl') !== 0)
		{
			return false;
		}
		
		$classname = $class;

		$paths = explode('\\', $class);
		
		array_shift($paths);
		array_shift($paths);
		array_shift($paths);
		
		$class = array_pop($paths);
		
		$directory = (count($paths) > 0) ? implode(DIRECTORY_SEPARATOR, $paths) : "";
		
		if($directory) $directory .= DIRECTORY_SEPARATOR;
		
		$filepath = dirname(__FILE__) . DIRECTORY_SEPARATOR . "sample" . DIRECTORY_SEPARATOR . $directory . $class . ".php";
		
		if(is_file($filepath))
		{
			require_once($filepath);
			
			if(method_exists($classname, '_init') and is_callable($classname.'::_init'))
			{
				call_user_func($classname.'::_init');
			}
			
			return true;
		}
		
		return false;
	}, true);
