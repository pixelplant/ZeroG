<?php
/**
* Main entrance point to the ZeroG framework
 * 
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @copyright Radu Mogos, www.pixelplant.ro
* @version 1.0.6
*/

namespace
{
	function error_handler($errno, $errstr, $errfile, $errline)
	{
		$error = sprintf('<p>%s - %s : %s @ %s</p>', $errno, $errstr, $errfile, $errline);

		throw
			new \Sys\Exception($error);
	}

	function shutdown_function()
	{
		$lastError = error_get_last();

		if ($lastError['type'] == E_ERROR || $lastError['type'] == E_USER_ERROR)
		{
			$error = 'Fatal error in file: '.$lastError['file'];
			die($error);
		}
	}
	set_error_handler('error_handler');
	register_shutdown_function('shutdown_function');

	define('ROOT_DIRECTORY', realpath(dirname(__FILE__)));
	set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_DIRECTORY);
	/*
	* Autoload settings. By default every namespace/class path links directly
	* to the folder/file path for each class. Which it should, like every other
	* normal programming language.
	*/
	\spl_autoload_extensions('.php');
	\spl_autoload_register();

	/**
	* error reporting settings
	*/
	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

	/**
	* define version number
	*/
	const ZEROG_VERSION = '1.0.6';

	try
	{
		// initialize and run the framework
		\Z::run();
		
		//$test = \Z::getModel('blog/post');
		//echo $test->getName();

		// test start - remove these lines
		/*$res = new Sys\Model\Resource("profiles/user");
		$res->getField('password')->setValue('fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			dsafsafsfsdfsdfdgdfgdgdgdgdgggggggggggggggggg');
		$res->getField('age')->setValue(13);
		var_dump($res->validateFields());
		var_dump($res);*/
		// test end

		//echo \Z::getProfiler();
		
	}
	catch (\Sys\Exception $e)
	{
		//header('Location: error/index.html');
		echo $e;
	}
}
