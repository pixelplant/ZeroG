<?php
/**
* Main entrance point to the ZeroG framework
 * 
* @author Radu Mogos <radu.mogos@pixelplant.ro>
* @copyright Radu Mogos, www.pixelplant.ro
* @version 1.0.8
*/

namespace
{
	define('ROOT_DIRECTORY', realpath(dirname(__FILE__)));
	set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_DIRECTORY);

	include "app/global.functions.php";

	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');

	// framework version number
	const ZEROG_VERSION = '1.0.8';

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

		//echo \Z::getProfiler();
		
	}
	catch (\Sys\Exception $e)
	{
		//header('Location: error/index.html');
		echo $e;
	}
}
