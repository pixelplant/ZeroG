<?php
/**
* Main entrance point to the zerog framework
 * 
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @copyright Radu Mogos, www.pixelplant.ro
* @version 1.0.6
*/

namespace
{
	function test($errno, $errstr, $errfile, $errline)
	{
		$error = sprintf('<p>%s - %s : %s @ %s</p>', $errno, $errstr, $errfile, $errline);
		throw
			new \Sys\Exception($error);
	}
	set_error_handler('test');

	require_once('z.php');
	/**
	* error reporting settings
	*/
	error_reporting(E_ALL);
	ini_set('display_errors', '1');

	/**
	* define version number
	*/
	const ZEROG_VERSION = '1.0.6';

	try
	{
		// initialize and run the framework
		\Z::run();

		// test start - remove these lines
		/*$res = new Sys\Model\Resource("profiles/user");
		$res->getField('password')->setValue('fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			fdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjgfdsafsadfsfg4yb546buy45ubj54buj54ub5jtjg
			dsafsafsfsdfsdfdgdfgdgdgdgdgggggggggggggggggg');
		$res->getField('age')->setValue(13);
		var_dump($res->validateFields());
		var_dump($res);*/
		// test end

		echo \Z::getProfiler();
		
	}
	catch (\Sys\Exception $e)
	{
		echo $e;
	}
}
